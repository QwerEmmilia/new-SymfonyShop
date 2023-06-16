<?php

namespace App\Controller;

use App\Entity\Goods;
use App\Entity\Order;
use App\Entity\OrderDetails;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;
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

        $cartItem = [
            'id' => $goods->getId(),
            'name' => $goods->getName(),
            'description' => $goods->getDescription(),
            'price' => $goods->getPrice(),
            'sizes' => $goods->getSizes(),
            'image' => $goods->getImage(),
            ];

        $cart[] = $cartItem;

        $session->set('cart', $cart);

        return $this->redirectToRoute('app_goodsPage', [
            'slug' => $goods->getSlug(),
        ]);
    }

    #[Route('/clear', name: 'app_clear_cart', methods: ['POST'])]
    public function clearCart(Request $request,SessionInterface $session): Response {
        $goodsId = $request->request->get('goods_id');

        $cart = $session->get('cart', []);

        if (array_key_exists($goodsId, $cart)) {
            unset($cart[$goodsId]);
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart', name: 'app_cart')]
    public function cart(SessionInterface $session): Response {
        $cart = $session->get('cart', []);
        $totalAmounts = 0;

        foreach ($cart as $item) {

            $totalAmounts += $item['price'];
        }

        return $this->render('cart.html.twig',[
            'cart' => $cart,
            'totalAmounts' => $totalAmounts,
        ]);
    }

    #[Route('/place_order', name: 'app_place_order', methods: ['POST'])]
    public function placeOrder(SessionInterface $session): Response {

        $cart = $session->get('cart', []);

        $totalAmount = 0;

        $newOrder = new Order();
        $newOrder->setOrderNumber(uniqid());

        foreach ($cart as $item) {
            $goods = $this->entityManager->getRepository(Goods::class)->find($item['id']);

            $totalAmount += $item['price'];

            $newOrderDetails = new OrderDetails();

            $newOrderDetails->setOrderId($newOrder);
            $newOrderDetails->setGoodsId($goods);

            $this->entityManager->persist($newOrderDetails);
        }

        $newOrder->setTotalAmount($totalAmount);
        $newOrder->setOrderDate(new DateTime());


        $this->entityManager->persist($newOrder);
        $this->entityManager->flush();

        $session->clear();

        return $this->redirectToRoute('app_order');
    }
}