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

    public function  __construct(EntityManagerInterface $entityManager, EmailService $emailService) {
        $this->entityManager = $entityManager;
        $this->emailService = $emailService;
    }

    #[Route('/subscribe', name: 'app_subscribe')]
    public function subscribe(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $isMaleSubscriber = $request->request->get('maleSubscription');
            $isFemaleSubscriber = $request->request->get('femaleSubscription');

            $isMaleSubscriber = isset($isMaleSubscriber) ? true : false;
            $isFemaleSubscriber = isset($isFemaleSubscriber) ? true : false;

            $existingSubscription = $this->entityManager->getRepository(Subscription::class)->findOneBy(['email' => $email]);
            if ($existingSubscription) {
                $this->addFlash('error',
                    "Ця пошта вже підписана на росилку.");
                return $this->redirectToRoute('app_main');
            }

            $subscribe = new Subscription();
            $subscribe->setEmail($email);
            $subscribe->setIsMaleSubscriber($isMaleSubscriber);
            $subscribe->setIsFemaleSubscriber($isFemaleSubscriber);

            $this->entityManager->persist($subscribe);
            $this->entityManager->flush();

            $this->emailService->sendSubscriptionNotification($email);
        }

        $this->addFlash('success',
            "Підписка успішно оформлена. Дякуємо!");
        return $this->redirectToRoute('app_main');
    }
}
