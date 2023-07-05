<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class EmailService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendSubscriptionNotification(string $email)
    {
        $email = (new TemplatedEmail())
            ->from('testdevemailqwer@gmail.com')
            ->to($email)
            ->subject('Розсилка')
            ->htmlTemplate('emails/subscription_notification.html.twig');

        $this->mailer->send($email);
    }
}