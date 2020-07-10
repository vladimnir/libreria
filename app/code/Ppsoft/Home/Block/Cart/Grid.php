<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ppsoft\Home\Block\Cart;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
class Grid extends \Magento\Checkout\Block\Cart\Grid
{
    /**
     * @var \Magento\Checkout\Model\Cart
     */
    protected $cart;
    /**
     * @var \Magento\Directory\Model\Currency
     */
    protected $currency;
    /**
     * @var ProductFactory
     */
    protected $_productloader;
    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;
    public function __construct(
        \Magento\Directory\Model\Currency $currency,
        \Magento\Checkout\Model\Cart $cart,
        ProductFactory $_productloader,
        ProductRepositoryInterface $productRepository,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Catalog\Model\ResourceModel\Url $catalogUrlBuilder,
        \Magento\Checkout\Helper\Cart $cartHelper,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Quote\Model\ResourceModel\Quote\Item\CollectionFactory $itemCollectionFactory,
        \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface $joinProcessor,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $customerSession,
            $checkoutSession,
            $catalogUrlBuilder,
            $cartHelper,
            $httpContext,
            $itemCollectionFactory,
            $joinProcessor,
            $data
        );
        $this->cart = $cart;
        $this->currency = $currency;
        $this->_productloader = $_productloader;
        $this->productRepository = $productRepository;
    }
    public function getTotal()
    {
        return $this->cart->getQuote()->getGrandTotal();
    }
    public function getCurrentCurrencySymbol()
    {
        return $this->currency->getCurrencySymbol();
    }
    public function getLoadProduct($id)
    {
        $loaded = $this->_productloader->create()->load($id);
        return $loaded;
    }
    public function getLoadProductBySku($sku)
    {
        return $this->productRepository->get($sku);
    }
    public function getServicios () {
        return $this->cart->getQuote()->getAllItems();
    }
    public function isCustomerLoggedIn()
    {
        return $this->_customerSession->isLoggedIn();
    }
    /**
     * @return int
     */
    public function hasMiniCuotas()
    {
        $boolres = 0;
        /** @var \Magento\Customer\Api\Data\CustomerInterface $customerDataModel */
        if ($customerDataModel = $this->_customerSession->getCustomerData()) {
            if ($customerDataModel->getCustomAttribute('pos_has_minicutoas') && !$this->hasPayMinicuota()) {
                return $customerDataModel->getCustomAttribute('pos_has_minicutoas')->getValue();
            }
            return $boolres;
        }
    }
    public function getSubTotal()
    {
        return $this->cart->getQuote()->getSubtotal();
    }
    public function hasPayMinicuota()
    {
        $quote = $this->cart->getQuote();
        foreach ($quote->getAllVisibleItems() as $item) {
            if ($item->getSku() == 'minicuota') {
                return true;
            }
        }
        return false;
    }
}