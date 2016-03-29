<?php

namespace Models;

use Models\Base\User as BaseUser;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Validator\Constraints\UniqueUserConstraint;

/**
 * Skeleton subclass for representing a row from the 'user' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class User extends BaseUser implements UserInterface, EquatableInterface
{

    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_USER = 'ROLE_USER';

    public function eraseCredentials()
    {
        $this->setPassword('');
        $this->setSalt('');
    }

    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof User) {
            return false;
        }

        if ($this->getPassword() !== $user->getPassword()) {
            return false;
        }

        if ($this->getSalt() !== $user->getSalt()) {
            return false;
        }

        if ($this->getUsername() !== $user->getUsername()) {
            return false;
        }

        return true;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addGetterConstraint('email', new Assert\Email([
            'message' => 'The email address is not valid',
        ]));
        $metadata->addGetterConstraint('email', new Assert\NotBlank([
            'message' => 'The email address should not be blank',
        ]));
        $metadata->addGetterConstraint('email', new UniqueUserConstraint());
        $metadata->addGetterConstraint('roles',
            new Assert\Callback(function (array $roles, ExecutionContextInterface $context) {
                if (!is_array($roles) || !count($roles)) {
                    $context->buildViolation('The user does not have any roles')
                        ->atPath('roles')
                        ->addViolation();
                }
            }));
        $metadata->addGetterConstraint('password', new Assert\NotBlank([
            'message' => 'The password cannot be blank',
        ]));
    }

}
