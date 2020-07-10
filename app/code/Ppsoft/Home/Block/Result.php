<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ppsoft\Home\Block;
use Magento\Catalog\Model\Layer\Resolver as LayerResolver;
use Magento\CatalogSearch\Helper\Data;
use Magento\Framework\View\Element\Template\Context;
use Magento\Search\Model\QueryFactory;


/**
 * Product search result block
 *
 * @api
 * @since 100.0.2
 */
class Result extends \Magento\CatalogSearch\Block\Result
{
    /**
     * @var \Magento\Directory\Model\Currency
     */
    protected $currency;

    public function __construct(Context $context,
                                LayerResolver $layerResolver,
                                Data $catalogSearchData,
                                QueryFactory $queryFactory,
                                \Magento\Directory\Model\Currency $currency,
                                array $data = [])
    {
        parent::__construct($context, $layerResolver, $catalogSearchData, $queryFactory, $data);
        $this->currency = $currency;
    }
    public function getCurrentCurrencySymbol()
    {
        return $this->currency->getCurrencySymbol();
    }

}
