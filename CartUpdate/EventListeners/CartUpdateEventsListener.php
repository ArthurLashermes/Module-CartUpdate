<?php

namespace CartUpdate\EventListeners;

use CartUpdate\Service\UpdateManagement;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Core\Event\Customer\CustomerLoginEvent;
use Thelia\Core\Event\Order\OrderProductEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Model\CartItemQuery;
use Thelia\Model\CartQuery;

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


    public function orderProdAfterCreate(OrderProductEvent $orderProductEvent)
    {

        $customerId = $this->management->getCustomerIdWithOrderProductEvent($orderProductEvent);
        $this->management->UpdateCart($customerId);
    }

    /**
     * @throws \Propel\Runtime\Exception\PropelException
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
            TheliaEvents::ORDER_PAY =>["orderProdAfterCreate", 128]
        ];
    }

}