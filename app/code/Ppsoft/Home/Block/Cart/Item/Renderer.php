<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ppsoft\Home\Block\Cart\Item;

use Magento\Catalog\Pricing\Price\ConfiguredPriceInterface;
use Magento\Checkout\Block\Cart\Item\Renderer\Actions;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Message\InterpretationStrategyInterface;
use Magento\Quote\Model\Quote\Item\AbstractItem;
use Magento\Catalog\Model\ProductFactory;

/**
 * Shopping cart item render block
 *
 * @api
 * @author      Magento Core Team <core@magentocommerce.com>
 *
 * @method \Magento\Checkout\Block\Cart\Item\Renderer setProductName(string)
 * @method \Magento\Checkout\Block\Cart\Item\Renderer setDeleteUrl(string)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Renderer extends \Magento\Checkout\Block\Cart\Item\Renderer
{
    /**
     * @var \Magento\Directory\Model\Currency
     */
    protected $currency;
    /**
     * @var \Magento\Checkout\Model\Cart
     */
    protected $cart;
    /**
     * @var ProductFactory
     */
    protected $_productloader;
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    public function __construct(
        \Magento\Directory\Model\Currency $currency,
        \Magento\Checkout\Model\Cart $cart,
        ProductFactory $_productloader,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Helper\Product\Configuration $productConfig,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Catalog\Block\Product\ImageBuilder $imageBuilder,
        \Magento\Framework\Url\Helper\Data $urlHelper,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        PriceCurrencyInterface $priceCurrency,
        \Magento\Framework\Module\Manager $moduleManager,
        InterpretationStrategyInterface $messageInterpretationStrategy,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        array $data = []
    ) {
        parent::__construct($context, $productConfig, $checkoutSession, $imageBuilder, $urlHelper, $messageManager, $priceCurrency, $moduleManager, $messageInterpretationStrategy, $data);
        $this->currency = $currency;
        $this->cart = $cart;
        $this->_productloader = $_productloader;
        $this->productRepository = $productRepository;
    }

    public function getCurrentCurrencySymbol()
    {
        return $this->currency->getCurrencySymbol();
    }
    public function getTotal()
    {
        return $this->cart->getQuote()->getGrandTotal();
    }
    public function getLoadProduct($id)
    {
        $loaded = $this->_productloader->create()->load($id);
        return $loaded;
    }

    public function getProductData($sku) {
        $price = $this->productRepository->get($sku)->getPrice();
        return $price;
    }

    public function getProductId($sku) {
        $id = $this->productRepository->get($sku)->getId();
        return $id;
    }

    public function getCartProduct($sku) {
        $items = $this->cart->getQuote()->getItems();

        foreach ($items as $item => $value) {
            $val = $value->getSku();
            if ($val == $sku ) {
                return true;
            }
        }
        return false;
    }

    private function lastItemId($items) {
        $max = 0;
        foreach ($items as $item => $value) {
            if ($value->getId() > $max) {
                $max = $value->getId();
            }
        }
        return $max;
    }
}
