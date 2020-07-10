<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ppsoft\CustomCheckout\Block\Onepage;

use Magento\Customer\Model\Context;
use Magento\Sales\Model\Order;
use Magento\Catalog\Model\ProductFactory;
/**
 * One page checkout success page
 *
 * @api
 * @since 100.0.2
 */
class Success extends \Magento\Checkout\Block\Onepage\Success
{
    /**
     * @var ProductFactory
     */
    protected $_productloader;
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $ordersucces;
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * @var \Magento\Sales\Model\Order\Config
     */
    protected $_orderConfig;

    /**
     * @var \Magento\Framework\App\Http\Context
     */
    protected $httpContext;
    /**
     * @var \Magento\Directory\Model\Currency
     */
    protected $currency;
    /**
     * @var \Magento\Framework\Pricing\Helper\Data
     */
    protected $pricing;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var \Magento\Framework\View\Element\BlockFactory
     */
    protected $blockFactory;
    /**

     * @var \Magento\Checkout\Model\Cart
     */
    private $cart;
    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    private $directoryList;
    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    private $fileDriver;
    /**
     * @var \Wyomind\PointOfSale\Model\ResourceModel\PointOfSale\CollectionFactory
     */
    private $posCollectionFactory;
    /**
     * @var \Magento\Quote\Model\QuoteFactory
     */
    private $quoteFactory;
    /**
     * @var \Ppsoft\PaymentMinicuotas\Helper\Data
     */
    private $minicuotasData;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Sales\Model\Order\Config $orderConfig
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Sales\Model\Order\Config $orderConfig,
        \Magento\Framework\App\Http\Context $httpContext,
        Order $ordersuccess,
        \Magento\Directory\Model\Currency $currency,
        \Magento\Framework\Pricing\Helper\Data $pricing,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\View\Element\BlockFactory $blockFactory,
        ProductFactory $_productloader,
        \Magento\Checkout\Model\Cart $cart,
        \Wyomind\PointOfSale\Model\ResourceModel\PointOfSale\CollectionFactory $posCollectionFactory,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\Filesystem\Driver\File $fileDriver,
        \Magento\Quote\Model\QuoteFactory $quoteFactory,
        \Ppsoft\PaymentMinicuotas\Helper\Data $minicuotasData,
        array $data = []
    ) {
        parent::__construct($context, $checkoutSession, $orderConfig, $httpContext, $data);
        $this->_checkoutSession = $checkoutSession;
        $this->_orderConfig = $orderConfig;
        $this->_isScopePrivate = true;
        $this->httpContext = $httpContext;
        $this->ordersucces = $ordersuccess;
        $this->currency = $currency;
        $this->pricing = $pricing;
        $this->_productloader = $_productloader;
        $this->storeManager = $storeManager;
        $this->blockFactory = $blockFactory;
        $this->cart = $cart;
        $this->directoryList = $directoryList;
        $this->fileDriver = $fileDriver;
        $this->posCollectionFactory = $posCollectionFactory;
        $this->quoteFactory = $quoteFactory;
        $this->minicuotasData = $minicuotasData;
    }
    public function getStoreName($orderid){
        $storeId = $this->ordersucces->loadByIncrementId($orderid)->getStoreId();
        $storeName = "";
        if($storeId){
            $storeName = $this->storeManager->getStore($storeId)->getName();
        }
        return $storeName;
    }
    public function getPaymentName($orderid) {
        return $this->ordersucces->loadByIncrementId($orderid)->getPayment()->getMethod();
    }
    public function getPaymentTitle($orderId){
        return $this->ordersucces->loadByIncrementId($orderId)->getPayment()->getMethodInstance()->getTitle();
    }
    public function getMinicuotasData($orderId){
        $quoteId = $this->ordersucces->getQuoteId($orderId);
        $quote = $this->cart->getQuote()->load($quoteId);
        $proformaCredito = json_decode($quote->getProformaCredito(),TRUE);

        $minicuotaData = array(
            'numeroCuotas' => $proformaCredito['numeroCuota'],
            'montoCuota' => $proformaCredito['montoCuota']
        );
        return $minicuotaData;
    }
    public function getOrderByIncrement($orderId){
        return $this->ordersucces->loadByIncrementId($orderId)->getId();
    }
    public function getTotal($orderid)
    {
        $totals = $this->ordersucces->loadByIncrementId($orderid)->getGrandTotal();
        return number_format($totals, '2', '.', ',');
    }
    public function getSubtotal($orderid)
    {
        $totals = $this->ordersucces->loadByIncrementId($orderid)->getSubtotal();
        return number_format($totals, '2', '.', ',');
    }
    public function getShipping($orderid)
    {
        $totals = $this->ordersucces->loadByIncrementId($orderid)->getShippingAmount();
        return number_format($totals, '2', '.', ',');
    }
    public function getCurrentCurrencySymbol()
    {
        return $this->currency->getCurrencySymbol();
    }
    public function getProductsQty($orderid) {
        $items = $this->ordersucces->loadByIncrementId($orderid)->getAllItems();
        $total_qty = 0;
        foreach ($items as $item){
            $total_qty = $total_qty + $item->getQtyOrdered();
        }
        return $total_qty;
    }
    public function getShippingName ($orderid) {
        $res = null;
        $shippingDescription =  $this->ordersucces->loadByIncrementId($orderid)->getShippingDescription();
        $order = $this->_checkoutSession->getLastRealOrder();
        $shippingMethod = $order->getShippingMethod();
        if (strpos($shippingMethod, 'pickupatstore_pickupatstore') !== false){
            $storeAddressSelected = explode("-", $shippingDescription);
            $res = $storeAddressSelected;
        }
        if(strpos($shippingMethod, 'Ppsoftshippinghomedelivery_Ppsoftshippinghomedelivery')!==false){
            $description = implode(' ',array_unique(explode(' ', $shippingDescription)));
            $res = str_replace('-', '', $description);
        }
        return $res;
    }
    public function getNumeroCompra($montoCuota){

        $data = $this->minicuotasData->getPaymentMinicuotas();
        $ultimaCompra = null;
        if($data){
            $detallePrestamos = $data['detallePrestamos']['modelo']['productos'][0]['productosDet'];
            foreach ($detallePrestamos as $credito => $data){
                if($data['saldo'] == $montoCuota){
                    $ultimaCompra = $data['idPrestamo'];
                    break;
                }
            }
        }
        return $ultimaCompra;
    }
    public function getStreet() {
        $order = $this->_checkoutSession->getLastRealOrder();
        $carrierCode = $order->getShippingMethod();

        if (stripos($carrierCode, "pickupatstore") !== false) {
            $storeId = str_replace("pickupatstore_", "", $carrierCode);
            $store = $this->posCollectionFactory->create()->getPlace($storeId)->getFirstItem();
            return $store->getAddressLine1();
        }

        return null;
    }
    public function getShippingMethod($orderid){
        return $this->ordersucces->loadByIncrementId($orderid)->getShippingMethod();
    }
    public function getShippingAddress($orderid){
        return $this->ordersucces->loadByIncrementId($orderid)->getShippingAddress();
    }
    public function getShippingCustomerName ($orderid) {
        return $this->ordersucces->loadByIncrementId($orderid)->getShippingAddress()->getName();
    }
    public function getShippingCustomerStreet ($orderid) {
        return $this->ordersucces->loadByIncrementId($orderid)->getShippingAddress()->getStreetLine(1);
    }
    public function getShippingCustomerCity ($orderid) {
        return $this->ordersucces->loadByIncrementId($orderid)->getShippingAddress()->getCity();
    }
    public function getCardNumbers ($orderid) {
        return $this->ordersucces->loadByIncrementId($orderid)->getPayment()->getCcLast4();
    }
    public function getCardName ($orderid) {
        return $this->ordersucces->loadByIncrementId($orderid)->getPayment()->getCcType();
    }
    public function getProductsData ($orderid) {
        return $this->ordersucces->loadByIncrementId($orderid)->getAllItems();
    }
    public function getProductLoaded($id)
    {
        $loaded = $this->_productloader->create()->load($id);
        return $loaded;
    }
    public function getDataEncrypted($data){
        return md5($data);
    }
    public function getNextPaymentMinicuota($orderId){
        $order_id   =   $this->getOrderByIncrement($orderId);
        $order      =   $this->ordersucces->load($order_id);

        $quoteId    =   $order->getQuoteId();
        $cartData   =   $this->cart->getQuote()->load($quoteId);
        $minicuotaPay = "";
        if($cartData){
            $cartDataArr =  json_decode($cartData->getProformaCredito(), true);
            $venc       =   $cartDataArr['diaVencimiento'];
            $date       =   date_create($order->getCreatedAt());
            $addDate    =   date_add($date,date_interval_create_from_date_string("{$venc} days"));
            $minicuotaPay = date_format($addDate, 'd/m/Y');
        }
        return $minicuotaPay;
    }
    public function getFileValidation($file){
        $dataEncrypt = md5($file);
        $file = $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA) .'/facturas/' . $dataEncrypt.'.pdf';

        if ($this->fileDriver->isExists($file)) {
            return $dataEncrypt;
        }
        return null;
    }
    /**
     * @param $orderId
     * @return bool
     */
    public function validateMinicuota($orderId) {
        $minicuota = 0;
        $order_id = $this->getOrderByIncrement($orderId);
        $order = $this->ordersucces->load($order_id);
        $quoteId = $order->getQuoteId();
        $cartData = $this->cart->getQuote()->load($quoteId);
        if($cartData->getIsMinicuota() == 1){
            $minicuota = 1;
        }
        return $minicuota;
    }

    public function getQrCode($orderId) {
        $result = '';
        if($orderId) {
            $order_id = $this->getOrderByIncrement($orderId);
            $order = $this->ordersucces->load($order_id);
            $result = json_decode($order->getData('qr_code'));
        }
        return $result;
    }
    public function getMinicuotaItem($orderId){
        $minicuota = 0;
        if($orderId){
            $order_id = $this->getOrderByIncrement($orderId);
            $order = $this->ordersucces->load($order_id);
            $quoteId = $order->getQuoteId();
            $quote = $this->quoteFactory->create()->load($quoteId);
            $items = $quote->getAllVisibleItems();
            foreach($items as $item){
                if($item->getSku() == "minicuota")
                    $minicuota = 1;
            }
        }
        return $minicuota;
    }

    public function getPaymentAdditionalHtml() {
        $htmlOutPut = '';
        /** @var \Magento\Sales\Model\Order $order */
        $order = $this->_checkoutSession->getLastRealOrder();
        $paymentMethod = $order->getPayment()->getMethod();
        $shippingMethod = $order->getShippingMethod();

        /** @var \Magento\Framework\View\Element\Template $genericBlock */
        $genericBlock = $this->_layout->createBlock(\Ppsoft\CustomCheckout\Block\Onepage\Success::class);
        switch ($paymentMethod) {
            case 'banktransfer':
                if (strpos($shippingMethod, 'pickupatstore_pickupatstore') !== false
                    || ($shippingMethod == 'Ppsoftshippinghomedelivery_Ppsoftshippinghomedelivery')
                ) {
                    $htmlOutPut = $genericBlock->setTemplate('Ppsoft_CustomCheckout::payment/banktransfer.phtml')->toHtml();
                }
                break;
        }
        return $htmlOutPut;
    }
    public function getMediaUrl($path)
    {
        return $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $path;
    }
}
