<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Ppsoft\Home\Block\Customer;
use Magento\Catalog\Model\Product;
use Magento\Customer\Model\Context;
use Magento\Framework\App\Action\Action;

/**
 * Catalog products compare block
 * @api
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @since 100.0.2
 */
class Info extends \Magento\Wishlist\Block\Customer\Wishlist\Item\Column\Info
{
    /**
     * @var \Magento\Directory\Model\Currency
     */
    protected $currency;

   public function __construct(\Magento\Directory\Model\Currency $currency, \Magento\Catalog\Block\Product\Context $context, \Magento\Framework\App\Http\Context $httpContext, array $data = [])
   {
       parent::__construct($context, $httpContext, $data);
       $this->currency = $currency;
   }

    public function getCurrentCurrencySymbol()
    {
        return $this->currency->getCurrencySymbol();
    }
}
