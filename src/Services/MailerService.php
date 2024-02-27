<?php

namespace App\Services;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class MailerService
{

    public function sendEmail(array $emailParameters){

        $user = $emailParameters['user'];
        $mailer = $emailParameters['mailer'];
        $objet = $emailParameters['objet'];
        $template = $emailParameters['template'];
        $resetUrl = $emailParameters['resetUrl'];

        // Check if resetToken is provided in the array
        $resetToken = $emailParameters['resetToken'] ?? null;


        $email = (new TemplatedEmail())
            ->from(new Address('aicha.mougni@gmail.com', 'SnowTricks Bot'))
            ->to($user->getEmail())
            ->subject($objet)
            ->htmlTemplate($template)
            ->context([
                'resetToken' => $resetToken,
                'resetUrl' => $resetUrl
            ])
        ;

        $mailer->send($email);
        return true;
    }

}