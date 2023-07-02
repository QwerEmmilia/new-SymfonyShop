<?php

namespace App\Controller;

use App\Entity\Goods;
use App\Entity\GoodsSize;
use App\Entity\Order;
use App\Entity\OrderDetails;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Turbo\TurboBundle;

class CartController extends AbstractController
{
    private $entityManager;

    public function  __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }
    #[Route('/add_to_cart', name: 'app_to_cart', methods: ['POST'])]
    public function addToCart(Request $request, SessionInterface $session): Response {

        $goodsId = $request->request->get('goods_id');
        $sizeId = $request->request->get('size_id');
        $goods = $this->entityManager->getRepository(Goods::class)->find($goodsId);
        $cart = $session->get('cart', []);

        $found = false;
        foreach ($cart as &$item) {
            if ($item['id'] == $goodsId && $item['sizeId'] == $sizeId ) {
                if ($item['quantity'] >= $item['maxQuantity']){
                    $this->addFlash('error',
                        "Пробачте, але в нас нема більшої кількості цього товару :(");
                    return $this->redirectToRoute('app_goodsPage', [
                        'slug' => $goods->getSlug(),
                    ]);
                }
                $item['quantity']++;
                $found = true;
                break;
            }
        }

        if (!$found) {
            foreach ($goods->getGoodsSizes() as $goodsSize) {
                if ($goodsSize->getSizeId()->getId() == $sizeId) {
                    $cartItem = [
                        'id' => $goods->getId(),
                        'name' => $goods->getName(),
                        'description' => $goods->getDescription(),
                        'price' => $goods->getPrice(),
                        'sizeId' => $sizeId,
                        'sizes' => $goodsSize->getSizeId()->getSize(),
                        'image' => $goods->getImage(),
                        'quantity' => 1,
                        'maxQuantity' => $goodsSize->getQuantity(),
                    ];

                    $cart[] = $cartItem;
                    break;
                }
            }
        }

        $session->set('cart', $cart);

        $this->addFlash('success',
            "Чудово! Товар було додано до кошика.");

        $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

        return $this->render('reviews.stream.html.twig',[
            'cart' => $cart,
        ]);
    }

    #[Route('/clear{id}', name: 'app_clear_cart', methods: ['POST'])]
    public function clearCart($id,SessionInterface $session): Response {

        $cart = $session->get('cart', []);

        foreach ($cart as $key => $item) {
            if ($item['id'] == $id) {
                unset($cart[$key]);
                break;
            }
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart', name: 'app_cart')]
    public function cart(SessionInterface $session): Response {
        $cart = $session->get('cart', []);
        $totalAmounts = 0;
        $goodsQuantity = 0;

        foreach ($cart as $item) {
            $totalAmounts += $item['price'] * $item['quantity'];
            $goodsQuantity += $item['quantity'];
        }
        return $this->render('cart.html.twig',[
            'cart' => $cart,
            'totalAmounts' => $totalAmounts,
            'goodsQuantity' => $goodsQuantity,
        ]);


    }

    #[Route('/update_cart/{action}', name: 'app_update_cart', methods: ['POST'])]
    public function updateCart($action, Request $request, SessionInterface $session): Response
    {
        $goodsId = $request->request->get('goods_id');
        $sizeId = $request->request->get('size_id');
        $cart = $session->get('cart', []);

        $totalAmounts = 0;
        $goodsQuantity = 0;

        foreach ($cart as $key => &$item) {
            if ($item['id'] == $goodsId && $item['sizeId'] == $sizeId) {
                if ($action == 'increase') {
                    if ($item['quantity'] >= $item['maxQuantity']) {
                        $this->addFlash('error-cart', "Ви додали максимальну кількість цього товару");
                        return $this->redirectToRoute('app_cart');
                    }
                    $item['quantity']++;
                } elseif ($action == 'decrease') {
                    if ($item['quantity'] > 1) {
                        $item['quantity']--;
                    } elseif ($item['quantity'] == 1) {
                        unset($cart[$key]);
                    }
                }
                break;
            }
        }

        foreach ($cart as &$item) {
            $totalAmounts += $item['price'] * $item['quantity'];
            $goodsQuantity += $item['quantity'];
        }
        $session->set('cart', $cart);

        $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

        return $this->render('reviews.stream.html.twig',[
            'cart' => $cart,
            'goodsQuantity' => $goodsQuantity,
            'totalAmounts' => $totalAmounts,
        ]);
    }


    #[Route('/place_order', name: 'app_place_order', methods: ['POST'])]
    public function placeOrder(SessionInterface $session): Response {

        $cart = $session->get('cart', []);

        $totalAmount = 0;
        $goodsTotal = 0;

        $newOrder = new Order();
        $newOrder->setOrderNumber(mt_rand(100000, 999999));

        foreach ($cart as $item) {
            $goods = $this->entityManager->getRepository(Goods::class)->find($item['id']);
            $goodsSize = $this->entityManager->getRepository(GoodsSize::class)->findOneBy([
                'goodsId' => $item['id'],
                'sizeId' => $item['sizeId']
            ]);

            if ($goodsSize->getQuantity() < $item['quantity']) {
                $this->addFlash('error-quantity-cart', 'Пробачте, але більшої кількість товару "'.$item['name'].'" з розміром "'.$item['sizes'].'" немає.');
                return $this->redirectToRoute('app_cart');
            }

            if ($goodsSize) {
                $newQuantity = $goodsSize->getQuantity() - $item['quantity'];
                $goodsSize->setQuantity($newQuantity);
            }

            $totalAmount += $item['price'] * $item['quantity'];
            $goodsTotal += $item['quantity'];

            $newOrderDetails = new OrderDetails();

            $newOrderDetails->setOrderId($newOrder);
            $newOrderDetails->setGoodsId($goods);
            $newOrderDetails->setPurchaseQuantity($item['quantity']);
            $newOrderDetails->setSize($item['sizes']);

            $this->entityManager->persist($newOrderDetails);
        }

        $newOrder->setTotalQuantity($goodsTotal);
        $newOrder->setTotalAmount($totalAmount);
        $newOrder->setOrderDate(new DateTime());


        $this->entityManager->persist($newOrder);
        $this->entityManager->flush();

        $session->clear();

        return $this->redirectToRoute('app_success_order');
    }

    #[Route('/successOrder', name: 'app_success_order')]
    public function successOrder(): Response    {

        $order = $this->entityManager->getRepository(Order::class)->findLastOrder();

        return $this->render('success_order.html.twig',[
            'order' => $order
        ]);
    }

}