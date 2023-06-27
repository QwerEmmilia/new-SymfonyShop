<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function getCartQuantity(): int
    {
        $cart = $this->session->get('cart', []);
        $cartQuantity = 0;
        foreach ($cart as $item) {
            $cartQuantity += $item['quantity'];
        }
        return $cartQuantity;
    }
}