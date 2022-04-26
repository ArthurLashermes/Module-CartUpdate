<?php

namespace CartUpdate\Service;

use CartUpdate\Model\CartUpdate;
use CartUpdate\Model\CartUpdateQuery;
use DateTime;
use Propel\Runtime\Exception\PropelException;
use Thelia\Core\Event\Customer\CustomerLoginEvent;
use Thelia\Core\Event\Order\OrderProductEvent;
use Thelia\Model\CartItemQuery;
use Thelia\Model\CartQuery;
use Thelia\Model\ProductPriceQuery;

class UpdateManagement
{

    const LIFE_TIME_CART_DAY = 15;

    /**
     * @throws PropelException
     */
    public function getCustomerIdWithOrderEvent(OrderEvent $orderEvent): int
    {
        $cartItemId = $orderEvent->getOrder()->getCartId();
        if($cartItemId == null){
            return $cartItemId;
        }



        $cart = CartQuery::create()
            ->filterById($cartItemId)
            ->findOne();

        return $cart->getCustomer()->getId();//controller le cas null de customer

    }

    public function getCustomerIdWithCustomerLoginEvent(CustomerLoginEvent $customerLoginEvent): int
    {
        return $customerLoginEvent->getCustomer()->getId();
    }

    /**
     * @throws PropelException
     */
    public function UpdateCart($customerId)
    {
        $cart = CartQuery::create()
            ->filterByCustomerId($customerId)
            ->findOne();

        if ($cart != null) {
            $date = $cart->getCreatedAt();
            $days = $date->format('d') + $date->format('m') * 30 + $date->format('Y') * (365.25);
            $dateNow = new DateTime();
            $daysNow = $dateNow->format('d') + $dateNow->format('m') * 30 + $dateNow->format('Y') * (365.25);

            $inter = $daysNow - $days;


            if ($inter >= self::LIFE_TIME_CART_DAY) {
                $this->deleteCustomerCart($customerId);
            }
            $this->updateCustomerCart($customerId);
        }
    }

    /**
     * @throws PropelException
     */
    public function deleteCustomerCart($customerId)
    {
        CartQuery::create()
            ->filterByCustomerId($customerId)
            ->deleteAll();
    }

    /**
     * @throws PropelException
     */
    public function updateCustomerCart($customerId)
    {
        $cart = CartQuery::create()
            ->filterByCustomerId($customerId)
            ->findOne();

        $cartItemList = CartItemQuery::create()
            ->filterByCartId($cart->getId())
            ->find();

        foreach ($cartItemList as $cartItem) {
            $product_price = ProductPriceQuery::create()
                ->filterByProductSaleElementsId($cartItem->getProductSaleElementsId())
                ->findOne();

            if ($cartItem->getPromoPrice() != $product_price->getPromoPrice()) {
                $cartItem
                    ->setPromoPrice($product_price->getPromoPrice())
                    ->save();
                $cartUpdate = $this->getCartUpdated($cartItem);
                $cartUpdate->setCodePromoChanged(true);
            }
            if ($product_price->getPrice() != $cartItem->getPrice()) {//les if en trop
                $cartItem
                    ->setPrice($product_price->getPrice())
                    ->save();
                $cartUpdate = $this->getCartUpdated($cartItem);
                $cartUpdate->setPriceChanged(true);
            }
        }
    }

    public function getCartUpdated($cartItem): CartUpdate
    {
        $cartUpdate = CartUpdateQuery::create()
            ->filterByCartId($cartItem->getCartId())
            ->findOne();
        if ($cartUpdate == null) {
            $cartUpdate = new CartUpdate();
        }
        return $cartUpdate->setCartId($cartItem->getCartId());
    }
}

