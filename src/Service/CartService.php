<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getCartQuantity(): int
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);
        $cartQuantity = 0;
        foreach ($cart as $item) {
            $cartQuantity += $item['quantity'];
        }
        return $cartQuantity;
    }
}