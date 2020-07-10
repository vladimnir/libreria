<?php
/**
 * Created by PhpStorm.
 * User: oscar
 * Date: 12-09-19
 * Time: 03:40 PM
 */

namespace Ppsoft\SalesEmail\Observer;


use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class ObserverforAddCustomVariable implements ObserverInterface
{

    /**
     * @var \Magento\Sales\Model\Order
     */
    private $order;

    public function __construct(\Magento\Sales\Model\Order $order)
    {
        $this->order = $order;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var \Magento\Framework\App\Action\Action $controller */
        $transport = $observer->getEvent()->getTransport();
        $order = $transport['order'];
        $orderId = $order->getEntityId();
        $orderIncrementId = $order->getIncrementId();
        $paymentMethod = $this->order->loadByIncrementId($orderIncrementId)->getPayment()->getMethod();
        $encryptedData = $this->setOrderIncrementIdEcrypted($orderIncrementId);
        $paymeFlag = 0;
        if($paymentMethod == "payme"){
            $paymeFlag = 1;
        }
        $transport['encryptedOrder'] = $encryptedData;
        if($paymeFlag == 0){
            $transport['paymentMethod'] =   $paymeFlag;
        }
    }

    public function setOrderIncrementIdEcrypted($orderIncrementId){
        return md5($orderIncrementId);
    }
}