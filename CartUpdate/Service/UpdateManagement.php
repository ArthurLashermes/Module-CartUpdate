<?php

namespace CartUpdate\Service;

use Thelia\Core\Event\Customer\CustomerLoginEvent;
use Thelia\Core\Event\Order\OrderProductEvent;
use Thelia\Model\CartItemQuery;
use Thelia\Model\CartQuery;

class UpdateManagement
{

    CONST LIFE_TIME_CARTDAY = 15;
    /**
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getCustomerIdWithOrderProductEvent(OrderProductEvent $orderProductEvent): int
    {
        $cartItemId = $orderProductEvent->getCartItemId();


        $cartItem = CartItemQuery::create()
            ->filterById($cartItemId)
            ->findOne();

        $cartId = $cartItem->getCartId();

        $cart = CartQuery::create()
            ->filterById($cartId)
            ->findOne();

         return $cart->getCustomer()->getId();

    }

    public function getCustomerIdWithCustomerLoginEvent(CustomerLoginEvent $customerLoginEvent): int
    {
        return $customerLoginEvent->getCustomer()->getId();
    }

    /**
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function UpdateCart($customerId)
    {
        $cart =CartQuery::create()
            ->filterByCustomerId($customerId)
            ->findOne();

        if($cart != null){
            $date=$cart->getCreatedAt();
            $days=$date->format('d')+$date->format('m')*30+$date->format('Y')*(365.25);
            $dateNow = new \DateTime();
            $daysNow = $dateNow->format('d')+$dateNow->format('m')*30+$dateNow->format('Y')*(365.25);

            $inter = $daysNow-$days;


            if($inter >= self::LIFE_TIME_CARTDAY){
                CartQuery::create()
                    ->filterByCustomerId($customerId)
                    ->deleteAll();
            }
        }
    }
}