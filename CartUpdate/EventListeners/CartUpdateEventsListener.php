<?php

namespace CartUpdate\EventListeners;

use CartUpdate\Service\UpdateManagement;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Core\Event\Customer\CustomerLoginEvent;
use Thelia\Core\Event\Order\OrderEvent;
use Thelia\Core\Event\Order\OrderPaymentEvent;
use Thelia\Core\Event\Order\OrderProductEvent;
use Thelia\Core\Event\Product\ProductUpdateEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Model\CartItemQuery;
use Thelia\Model\CartQuery;
use Thelia\Model\Event\ProductEvent;

class CartUpdateEventsListener implements EventSubscriberInterface
{
    /**
     * @var UpdateManagement
     */
    private $management;

    public function __construct(UpdateManagement $management)
    {
        $this->management = $management;
    }


    /**
     * @throws PropelException
     */
    public function orderProdAfterCreate(OrderEvent $orderEvent)
    {
        $customerId = $this->management->getCustomerIdWithOrderEvent($orderEvent);
        $this->management->UpdateCart($customerId);
    }

    /**
     * @throws PropelException
     */
    public function customerAfterLogin(CustomerLoginEvent $customerLoginEvent)
    {

        $customerId = $this->management->getCustomerIdWithCustomerLoginEvent($customerLoginEvent);
        $this->management->UpdateCart($customerId);
    }


    public static function getSubscribedEvents(): array
    {
        return [
            TheliaEvents::CUSTOMER_LOGIN => ["customerAfterLogin", 1],
            TheliaEvents::ORDER_PAY =>["orderProdAfterCreate", 128],
        ];
    }

}