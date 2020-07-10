<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * @author     Magento Core Team <core@magentocommerce.com>
 */
namespace Ppsoft\Home\Block\Customer;

/**
 * Wishlist block customer items.
 *
 * @api
 * @since 100.0.2
 */
class Wishlist extends \Magento\Wishlist\Block\Customer\Wishlist
{

    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Catalog\Helper\Product\ConfigurationPool $helperPool,
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer,
        \Magento\Framework\Data\Helper\PostHelper $postDataHelper,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $httpContext,
            $helperPool,
            $currentCustomer,
            $postDataHelper,
            $data
        );
    }

    protected function _prepareLayout()
    {
        $this->pageConfig->getTitle()->set(__('My Wish List'));

        return $this;
    }


}
