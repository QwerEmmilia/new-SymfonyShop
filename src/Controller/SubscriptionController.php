<?php

namespace App\Controller;

use App\Entity\Subscription;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SubscriptionController extends AbstractController
{
    private $entityManager;
    private $emailService;

    public function __construct(EntityManagerInterface $entityManager, EmailService $emailService)
    {
        $this->entityManager = $entityManager;
        $this->emailService = $emailService;
    }

    #[Route('/subscribe', name: 'app_subscribe', methods: ['POST'])]
    public function subscribe(Request $request): Response
    {
        $email = $request->request->get('email');
        $isMaleSubscriber = $request->request->get('maleSubscription');
        $isFemaleSubscriber = $request->request->get('femaleSubscription');

        $isMaleSubscriber = isset($isMaleSubscriber) ? true : false;
        $isFemaleSubscriber = isset($isFemaleSubscriber) ? true : false;

        $existingSubscription = $this->entityManager->getRepository(Subscription::class)->findOneBy(['email' => $email]);
        if ($existingSubscription) {
            $this->addFlash('error', 'Ця пошта вже підписана на розсилку.');
            return $this->redirectToRoute('app_main');
        }

        $subscription = new Subscription();
        $subscription->setEmail($email);
        $subscription->setIsMaleSubscriber($isMaleSubscriber);
        $subscription->setIsFemaleSubscriber($isFemaleSubscriber);

        $this->entityManager->persist($subscription);
        $this->entityManager->flush();

        $this->emailService->sendSubscriptionNotification($email);

        $this->addFlash('success', 'Підписка успішно оформлена. Дякуємо!');
        return $this->redirectToRoute('app_main');
    }
}

