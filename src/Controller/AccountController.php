<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    private $entityManager;

    public function  __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    #[Route('/order/history', name: 'app_order')]
    public function order(): Response {

        $orders = $this->entityManager->getRepository(Order::class)->findAll();

        return $this->render('order_history.html.twig',[
            'orders' => $orders
        ]);
    }

}