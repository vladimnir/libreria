<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ppsoft\Home\Block\Product;

/**
 * Product View block
 * @api
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @since 100.0.2
 */
class View extends \Magento\Catalog\Block\Product\View
{

    /**
     * @var \Magento\CatalogInventory\Api\StockStateInterface
     */
    protected $stockState;
    protected $stockRegistry;
    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productloader;
    /**
     * @var \Magento\Directory\Model\Currency
     */
    protected $currency;

    /**
     * View constructor.
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Framework\Url\EncoderInterface $urlEncoder
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Magento\Framework\Stdlib\StringUtils $string
     * @param \Magento\Catalog\Helper\Product $productHelper
     * @param \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig
     * @param \Magento\Framework\Locale\FormatInterface $localeFormat
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     * @param \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
     * @param \Magento\CatalogInventory\Api\StockStateInterface $stockState
     * @param \Magento\Catalog\Model\ProductFactory $_productloader
     * @param \Magento\Directory\Model\Currency $currency
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Catalog\Helper\Product $productHelper,
        \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig,
        \Magento\Framework\Locale\FormatInterface $localeFormat,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\CatalogInventory\Api\StockStateInterface $stockState,
        \Magento\Catalog\Model\ProductFactory $_productloader,
        \Magento\Directory\Model\Currency $currency,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $urlEncoder,
            $jsonEncoder,
            $string,
            $productHelper,
            $productTypeConfig,
            $localeFormat,
            $customerSession,
            $productRepository,
            $priceCurrency,
            $data
        );
        $this->stockState = $stockState;
        $this->stockRegistry = $stockRegistry;
        $this->_productloader = $_productloader;
        $this->currency = $currency;
    }
    public function getStock() {
         return $this->stockState->getStockQty($this->getProduct()->getId(), $this->getProduct()->getStore()->getWebsiteId());
    }
    public function getQtyConfig() {
        $_product = $this->getProduct();
        $varp = [];
        if($_product->getTypeId() == 'configurable'){
            $asoP = $_product->getTypeInstance()->getUsedProducts($this->getProduct());
            foreach ($asoP as $p) {
                $varp[$p->getId()] = $this->_productloader->create()->load($p->getId())->getExtensionAttributes()->getStockItem()->getQty();
            }
        }


        return $varp;
    }

    /**
     * get current currency symbol
     * @return string
     */
    public function getCurrentCurrencySymbol() {

        $symbol = $this->currency->getCurrencySymbol();

        return $symbol;
    }
}
