<?php

namespace App\Controller;

use App\Entity\Goods;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private $entityManager;

    public function  __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }
    #[Route('/add_to_cart', name: 'app_to_cart', methods: ['POST'])]
    public function addToCart(Request $request, SessionInterface $session): Response {

        $goodsId = $request->request->get('goods_id');

        $goods = $this->entityManager->getRepository(Goods::class)->find($goodsId);

        $cart = $session->get('cart', []);

        $cart[] = $goods;

        $session->set('cart', $cart);

        $this->addFlash('success', 'Vote counted!');

        return $this->redirectToRoute('app_goodsPage', [
            'slug' => $goods->getSlug()
        ]);
    }

    #[Route('/cart', name: 'app_cart')]
    public function cart(SessionInterface $session): Response {
        $cart = $session->get('cart', []);

        return $this->render('cart.html.twig',[
            'cart' => $cart,
        ]);
    }

    #[Route('/clear', name: 'clear_cart', methods: ['POST'])]
    public function clearCart(SessionInterface $session): Response {

        $session->clear();

        return $this->redirectToRoute('app_cart');

    }
}