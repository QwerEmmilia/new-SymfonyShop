<?php

namespace App\EventListener;

use App\Service\CartService;
use Symfony\Component\HttpKernel\Event\ViewEvent;

class CartQuantityListener
{
    private $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function onKernelView(ViewEvent $event)
    {
        $cartQuantity = $this->cartService->getCartQuantity();
        $controllerResult = $event->getControllerResult();

        if (is_array($controllerResult)) {
            $controllerResult['cartQuantity'] = $cartQuantity;
            $event->setControllerResult($controllerResult);
        }
    }
}