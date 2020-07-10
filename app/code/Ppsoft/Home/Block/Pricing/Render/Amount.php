<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Ppsoft\Home\Block\Pricing\Render;

use Magento\Framework\Pricing\Amount\AmountInterface;
use Magento\Framework\Pricing\SaleableInterface;
use Magento\Framework\Pricing\Price\PriceInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Pricing\Render\RendererPool;


class Amount extends \Magento\Framework\Pricing\Render\Amount
{
    /**
     * @var \Magento\Directory\Model\Currency
     */
    protected $currency;
    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    private $productRepository;


    public function __construct(
        Template\Context $context,
        AmountInterface $amount,
        PriceCurrencyInterface $priceCurrency,
        RendererPool $rendererPool,
        SaleableInterface $saleableItem = null,
        PriceInterface $price = null,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Directory\Model\Currency $currency,
        array $data = []
    ){
        parent::__construct($context, $amount, $priceCurrency, $rendererPool, $saleableItem,$price, $data);

        $this->currency = $currency;
        $this->productRepository = $productRepository;
    }
    public function getCurrentCurrencySymbol()
    {
        return $this->currency->getCurrencySymbol();
    }
    public function getProductPrices($productId){
        $prices = array();
        $product = $this->productRepository->getById($productId);
        $prices['price']            =   number_format($product->getPrice(), '2','.',',');
        $prices['special_price']    =   number_format($product->getSpecialPrice(), '2', '.', ',');

        return $prices;

    }


}
