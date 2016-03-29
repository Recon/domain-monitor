<?php


namespace Util\Config\Install;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * Maps the data sent via the install form and aids validation
 */
class InstallConfiguration
{

    public $db_host;
    public $db_port;
    public $db_name;
    public $db_user;
    public $db_pass;
    public $mail_transport;
    public $smtp_server;
    public $smtp_port;
    public $smtp_user;
    public $smtp_pass;
    public $smtp_encryption;
    public $admin_email;
    public $admin_pass;
    public $admin_pass_repeat;

    public function setData(array $data)
    {
        $reflection = new \ReflectionClass($this);
        $default = [];
        foreach ($reflection->getProperties() as $property) {
            $default[$property->getName()] = '';
        }

        $data = array_merge($default, $data);

        foreach ($data as $k => $item) {
            $this->{$k} = $item;
        }
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('db_host', new Assert\NotBlank([
            'message' => 'The database host is required'
        ]));
        $metadata->addPropertyConstraint('db_port', new Assert\Type([
            'type'    => 'numeric',
            'message' => 'The database server port has to be a number'
        ]));
        $metadata->addPropertyConstraint('db_name', new Assert\NotBlank([
            'message' => 'The database name is required'
        ]));
        $metadata->addPropertyConstraint('db_user', new Assert\NotBlank([
            'message' => 'The database user is required'
        ]));
        $metadata->addPropertyConstraint('db_pass', new Assert\NotBlank([
            'message' => 'The database password is required'
        ]));
        $metadata->addPropertyConstraint('mail_transport', new Assert\NotBlank([
            'message' => 'A mail transport option is required'
        ]));
        $metadata->addPropertyConstraint('mail_transport', new Assert\Choice([
            'groups'  => ['smtp'],
            'message' => 'The mail transport you selected is not a valid choice.',
            'choices' => ["mail", "smtp"]
        ]));


        $metadata->addPropertyConstraint('smtp_server', new Assert\NotBlank([
            'groups'  => ['smtp'],
            'message' => 'A SMTP server required',
        ]));
        $metadata->addPropertyConstraint('smtp_port', new Assert\NotBlank([
            'groups'  => ['smtp'],
            'message' => 'A SMTP port required',
        ]));
        $metadata->addPropertyConstraint('smtp_user', new Assert\NotBlank([
            'groups'  => ['smtp'],
            'message' => 'A SMTP user is required',
        ]));
        $metadata->addPropertyConstraint('smtp_pass', new Assert\NotBlank([
            'groups'  => ['smtp'],
            'message' => 'A SMTP password is required',
        ]));
        $metadata->addPropertyConstraint('smtp_encryption', new Assert\Choice([
            'groups'  => ['smtp'],
            'message' => 'The SMTP encryption you selected is not a valid choice.',
            'choices' => ["", "ssl", "tls"]
        ]));


        $metadata->addPropertyConstraint('admin_email', new Assert\NotBlank([
            'message' => 'The administrator email address is required'
        ]));
        $metadata->addPropertyConstraint('admin_email', new Assert\Email([
            'message' => 'The administrator email address is not valid'
        ]));

        $metadata->addPropertyConstraint('admin_pass', new Assert\NotBlank([
            'message' => 'The administrator password cannot be empty'
        ]));

        $passwordLengthConstraint = new Assert\Length([
            'min' => 5,
            'max' => 24,
        ]);
        $passwordLengthConstraint->minMessage = 'The administrator password is too short. It should have {{ limit }} character or more.|The administrator password is too short. It should have {{ limit }} characters or more.';
        $passwordLengthConstraint->maxMessage = 'The administrator password is too long. It should have {{ limit }} character or less.|The administrator password is too long. It should have {{ limit }} characters or less.';
        $metadata->addPropertyConstraint('admin_pass', $passwordLengthConstraint);

        $metadata->addPropertyConstraint('admin_pass_repeat',
            new Assert\Callback(function ($object, ExecutionContextInterface $context) {

                $model = $context->getObject();
                if ($model->admin_pass !== $model->admin_pass_repeat) {
                    $context->buildViolation('The administrator passwords are not identical')
                        ->atPath('admin_pass_repeat')
                        ->addViolation();
                }
            }));
    }
}
