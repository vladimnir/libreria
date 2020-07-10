<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Ppsoft\Home\Block\Product\Compare;
use Magento\Catalog\Model\Product;
use Magento\Customer\Model\Context;
use Magento\Framework\App\Action\Action;

/**
 * Catalog products compare block
 * @api
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @since 100.0.2
 */
class ListCompare extends \Magento\Catalog\Block\Product\Compare\ListCompare
{
    /**
     * @var \Magento\Directory\Model\Currency
     */
    protected $currency;

    public function __construct(\Magento\Directory\Model\Currency $currency, \Magento\Catalog\Block\Product\Context $context, \Magento\Framework\Url\EncoderInterface $urlEncoder, \Magento\Catalog\Model\ResourceModel\Product\Compare\Item\CollectionFactory $itemCollectionFactory, Product\Visibility $catalogProductVisibility, \Magento\Customer\Model\Visitor $customerVisitor, \Magento\Framework\App\Http\Context $httpContext, \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer, array $data = [])
    {
        parent::__construct($context, $urlEncoder, $itemCollectionFactory, $catalogProductVisibility, $customerVisitor, $httpContext, $currentCustomer, $data);
        $this->currency = $currency;
    }
    public function getCurrentCurrencySymbol()
    {
        return $this->currency->getCurrencySymbol();
    }
}
