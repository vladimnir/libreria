<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Ppsoft\Home\Block\Catalog\Product\View\AddTo;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Category;

/**
 * Product view wishlist block
 *
 * @api
 * @since 100.1.1
 */
class Wishlist extends \Magento\Wishlist\Block\Catalog\Product\View\AddTo\Wishlist
{
    protected $wishlist;
    protected $registry;
    protected $productRepository;
    protected $customerSession;
    public function __construct(\Magento\Catalog\Block\Product\Context $context,
                                \Magento\Framework\Url\EncoderInterface $urlEncoder,
                                \Magento\Framework\Json\EncoderInterface $jsonEncoder,
                                \Magento\Framework\Stdlib\StringUtils $string,
                                \Magento\Catalog\Helper\Product $productHelper,
                                \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig,
                                \Magento\Framework\Locale\FormatInterface $localeFormat,
                                \Magento\Customer\Model\Session $customerSession,
                                ProductRepositoryInterface $productRepository,
                                \Magento\Wishlist\Model\Wishlist $wishlist,
                                \Magento\Framework\Registry $registry,
                                \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
                                array $data = [])
    {
        parent::__construct($context, $urlEncoder, $jsonEncoder, $string, $productHelper, $productTypeConfig, $localeFormat, $customerSession, $productRepository, $priceCurrency, $data);
        $this->customerSession = $customerSession;
        $this->registry = $registry;
        $this->wishlist = $wishlist;
    }
    public function getWishlistCollection($customerId) {
         return $this->wishlist->loadByCustomerId($customerId)->getItemCollection();
    }
    public function getIdCustomer() {
        return $this->getCustomerId();
    }
}
