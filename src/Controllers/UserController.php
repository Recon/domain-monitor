<?php

namespace Controllers;

use Events\UserEvent;
use Exceptions\HTTP\JSON\UnauthorizedException;
use Models\DomainQuery;
use Models\User;
use Models\UserQuery;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Propel;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{

    public function getAuthenticatedUserInfo()
    {
        if (!$this->permissionChecker->isLoggedIn()) {
            throw new UnauthorizedException();
        }

        $username = $this->session->get('auth_token')->getUsername();

        $user = UserQuery::create()->findOneByEmail($username);
        $user->eraseCredentials();

        return new JsonResponse($user->toArray(TableMap::TYPE_FIELDNAME));
    }

    public function getUserInfo()
    {
        if (!$this->permissionChecker->isAdmin()) {
            throw new UnauthorizedException();
        }

        $user = UserQuery::create()->findOneById((int)$this->request->get('id'));

        if (!$user) {
            return new JsonResponse([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        $user->eraseCredentials();

        $data = $user->toArray(TableMap::TYPE_FIELDNAME);
        $data['domains'] = $user->getDomains()->toArray(null, null, TableMap::TYPE_FIELDNAME);

        return new JsonResponse($data);
    }

    public function addUser()
    {
        if (!$this->permissionChecker->isAdmin()) {
            throw new UnauthorizedException();
        }

        /* @var $encoderFactory EncoderFactory */
        $encoderFactory = $this->container->get('auth.encoder');
        /* @var $validator ValidatorInterface */
        $validator = $this->container->get('validator');
        $connection = Propel::getConnection();
        $connection->beginTransaction();

        $user = new User();
        $salt = random_str(32);
        $user->setAccount($this->getLoggedInUser()->getAccount());
        $user->setUsername($this->request->get('email'));
        $user->setEmail($this->request->get('email'));
        $user->addRole(User::ROLE_USER);
        if (filter_var($this->request->get('is_administrator'), FILTER_VALIDATE_BOOLEAN)) {
            $user->addRole(User::ROLE_ADMIN);
        }

        $user->setSalt($salt);
        $password = $this->request->get('password');
        $user->setPassword($password ? $encoderFactory->getEncoder($user)->encodePassword($password, $salt) : '');

        $this->setUserDomains($user, $this->request->get('domains', []));

        $messages = $this->getErrorMessages($user);

        if ($this->request->get('password') != $this->request->get('password2')) {
            $messages[] = "The two passwords do not match";
        }

        if (count($messages)) {
            $connection->rollBack();
            return new JsonResponse([
                'success'  => false,
                'messages' => $messages,
            ], 400);
        }

        $user->save(); // Save after validation due to unique keys
        $connection->commit();

        $this->container->get('event_dispatcher')->dispatch(UserEvent::NAME_ADDED, new UserEvent($user));

        return new JsonResponse([
            'success' => true,
        ]);
    }

    public function updateUser()
    {
        if (!$this->permissionChecker->isAdmin() && !($this->getLoggedInUser()->getId() == $this->request->get('id'))) {
            throw new UnauthorizedException();
        }

        /* @var $encoderFactory EncoderFactory */
        $encoderFactory = $this->container->get('auth.encoder');
        $messages = [];
        $connection = Propel::getConnection();

        $user = UserQuery::create()->findOneById((int)$this->request->get('id'));
        if (!$user) {
            return new JsonResponse([
                'success'  => false,
                'messages' => ['The user was not found'],
            ], 404);
        }

        $connection->beginTransaction();

        $user->setEmail($this->request->get('email'));
        $user->setUsername($this->request->get('email'));
        if ($this->request->get('password')) {
            if ($this->request->get('password') == $this->request->get('password2')) {
                $user->setPassword($encoderFactory->getEncoder($user)->encodePassword($this->request->get('password'),
                    $user->getSalt()));
            } else {
                $messages[] = 'The passwords do not match';
            }
        }

        if ($this->permissionChecker->isAdmin()) {
            if (!empty($this->request->get('domains'))) {
                $this->setUserDomains($user, $this->request->get('domains', []));
            }
        }

        // Only allow permission changes for other users
        if ($this->getLoggedInUser()->getId() != $user->getId() && $this->permissionChecker->isAdmin()) {
            if (filter_var($this->request->get('is_administrator'), FILTER_VALIDATE_BOOLEAN)) {
                $user->addRole(User::ROLE_ADMIN);
            } else {
                $user->removeRole(User::ROLE_ADMIN);
            }
        }

        $messages = array_merge($messages, $this->getErrorMessages($user));
        if (count($messages)) {
            $connection->rollBack();
            return new JsonResponse([
                'success'  => false,
                'messages' => $messages,
            ], 400);
        }

        $user->save();
        $connection->commit();

        $this->container->get('event_dispatcher')->dispatch(UserEvent::NAME_UPDATED, new UserEvent($user));

        return new JsonResponse([
            'success' => true,
        ]);
    }

    public function deleteUser()
    {
        if (!$this->permissionChecker->isAdmin()) {
            throw new UnauthorizedException();
        }

        $user = UserQuery::create()->findOneById((int)$this->request->get('id'));
        if (!$user) {
            return new JsonResponse([
                'success'  => false,
                'messages' => ['The user was not found'],
            ], 404);
        }

        if ($this->getLoggedInUser()->getAccount()->getId() != $user->getAccount()->getId()) {
            throw new UnauthorizedException();
        }

        if ($this->getLoggedInUser()->getId() == $user->getId()) {
            return new JsonResponse([
                'success'  => false,
                'messages' => ['You can\'t remove our own account'],
            ], 400);
        }

        $user->delete();

        return new JsonResponse([
            'success' => true,
        ]);
    }

    protected function setUserDomains(User $user, array $domains)
    {
        $domainCollection = new \Propel\Runtime\Collection\Collection();

        foreach ($domains As $domainData) {
            $domain = DomainQuery::create()->findOneById((int)$domainData['id']);
            if ($domain && $domain->getAccount()->getId() == $user->getAccount()->getId()) {
                $domainCollection->push($domain);
            }
        }

        $user->setDomains($domainCollection);
    }

}
