<?php

namespace App\Services;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class MailerService
{

    public function sendEmail($user, $mailer, $resetToken){
        $email = (new TemplatedEmail())
            ->from(new Address('aicha.mougni@gmail.com', 'SnowTricks Bot'))
            ->to($user->getEmail())
            ->subject('Réinitialisation du mot de passe')
            ->htmlTemplate('reset_password/email.html.twig')
            ->context([
                'resetToken' => $resetToken,
            ])
        ;

        $mailer->send($email);
        return true;
    }

}