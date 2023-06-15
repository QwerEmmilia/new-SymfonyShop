<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController
{
    private $entityManager;

    public function  __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }
}