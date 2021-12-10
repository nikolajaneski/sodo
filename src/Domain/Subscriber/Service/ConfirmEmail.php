<?php

namespace App\Domain\Subscriber\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final class ConfirmEmail
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendConfirmationEmail(array $formData): void
    {
        $url = "http://".$_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
        
        $message = "
            Hi, ". $formData['first_name'] . " <br /> 
            
            Please confirm your subscription on the following link: <br />
            <a href='".$url."/api/confirm/". $formData['emailHash'] ."/".$formData['hash'].
                    "' />".$url."/api/confirm/". $formData['emailHash'] ."/".$formData['hash']."</a>
        ";
        // Send email
        $email = (new Email())
            ->from('contact@sodo.com')
            ->to($formData['email'])
            ->subject('Sodo email confirmation')
            ->html($message);

        $this->mailer->send($email);
    }
}