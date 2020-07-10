<?php
/**
 * Ppsoft ControlMinicuotas
 *
 * @category    Wagneto
 * @package     Ppsoft_PaymentMinicuotas
 * @author      Rulo
 *
 */
namespace Ppsoft\Home\Observer;


use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\StoreManagerInterface;

class AfterShipment implements ObserverInterface
{
    /**
     * @param Observer $observer
     * @return void
     */
    private $storeManager;
    protected $_checkoutSession;
    protected $sender;
    protected $order;
    protected $ship;

    public function __construct(
        StoreManagerInterface $storeManager,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Sales\Model\Order\Email\Sender\ShipmentSender $sender,
        \Magento\Sales\Model\Order\Shipment $ship,
        \Magento\Sales\Model\Order $order

    ) {
        $this->storeManager = $storeManager;
        $this->_checkoutSession = $checkoutSession;
        $this->sender = $sender;
        $this->order = $order;
        $this->ship = $ship;

    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $orderp = $observer->getEvent()->getShipment();

        $this->sender->send($orderp, true); //pass second param if you want to send the email asynchronous - otherwise it will be send by running corresponding cron job
        return;
    }
}