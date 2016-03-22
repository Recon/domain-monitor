<?php

namespace Controllers;

use \DateTime;
use \Exceptions\HTTP\JSON\InvalidResetToken;
use \Models\UserQuery;
use \Propel\Runtime\ActiveQuery\Criteria;
use \Symfony\Component\HttpFoundation\JsonResponse;
use \Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use \Symfony\Component\Security\Core\Exception\AuthenticationException;

class AuthenticationController extends AbstractController
{

    public function login()
    {
        $username = $this->request->get('username');
        $password = $this->request->get('password');

        try {
            // Create "unauthenticated" token and authenticate it
            $token = new UsernamePasswordToken($username, $password, 'main', []);
            $token = $this->container->get('auth.manager')->authenticate($token);

            // Store "authenticated" token in the token storage
            $this->container->get('auth.token_storage')->setToken($token);

            $this->session->set('auth_token', $token);

            return new JsonResponse([
                'success' => true
            ]);
        } catch (AuthenticationException $e) {
            return new JsonResponse([
                'success' => false,
                'message' => $e->getMessage()
                ], 400);
        }
    }

    public function requestReset()
    {
        $username = filter_var($this->request->get('username'), FILTER_VALIDATE_EMAIL);
        $user = UserQuery::create()->findOneByUsername($username);

        if (!$user) {
            return new JsonResponse(); // Don't expose available/unavailable usernames
        }

        $user->setRecoveryDate(new DateTime());
        $user->setRecoveryToken(random_str(32));
        $user->save();

        $mailer = $this->container->get('mailer');
        $mailer->renderMessageBody('email/reset', [
            'recovery_link' => $this->request->getUriForPath(sprintf('/#/reset-password/%s', $user->getRecoveryToken()))
        ]);
        $mailer->getMessage()->setSubject("[Website Monitor] Password request");
        $mailer->getMessage()->setTo($user->getEmail());
        $mailer->getMessage()->setFrom('websitemonitor@localhost');
        $mailer->send();

        return new JsonResponse();
    }

    public function performReset()
    {
        $token = preg_replace('/[^a-z0-9]*/i', '', $this->request->get('token'));

        $user = UserQuery::create()
            ->filterByRecoveryToken($token)
            ->filterByRecoveryDate(time() - 3600 * 24, Criteria::GREATER_EQUAL)
            ->findOne();

        if (!$user) {
            throw new InvalidResetToken();
        }


        /* @var $encoderFactory EncoderFactory */
        $encoderFactory = $this->container->get('auth.encoder');
        $messages = [];
        if ($this->request->get('password') == $this->request->get('password2')) {
            $password = $encoderFactory->getEncoder($user)->encodePassword($this->request->get('password'), $user->getSalt());
            $user->setPassword($password);
        } else {
            $messages[] = 'The passwords do not match';
        }

        $messages = array_merge($messages, $this->getErrorMessages($user));
        if (count($messages)) {
            return new JsonResponse([
                'success' => false,
                'messages' => $messages
                ], 400);
        }

        $user->setRecoveryToken(null);
        $user->setRecoveryDate(null);
        $user->save();

        return new JsonResponse([
            'success' => true
        ]);
    }

}
