<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ppsoft\Compare\Helper\Product;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product\Compare\Item\Collection;

class Compare extends \Magento\Catalog\Helper\Product\Compare
{
    /**
     * @var \Magento\Review\Model\ReviewFactory
     */
    private $reviewFactory;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var \Magento\Catalog\Block\Product\Compare\ListCompare
     */
    private $listCompare;

    public function __construct(\Magento\Framework\App\Helper\Context $context,
                                \Magento\Store\Model\StoreManagerInterface $storeManager,
                                \Magento\Catalog\Model\ResourceModel\Product\Compare\Item\CollectionFactory $itemCollectionFactory,
                                Product\Visibility $catalogProductVisibility,
                                \Magento\Customer\Model\Visitor $customerVisitor,
                                \Magento\Customer\Model\Session $customerSession,
                                \Magento\Catalog\Model\Session $catalogSession,
                                \Magento\Framework\Data\Form\FormKey $formKey,
                                \Magento\Wishlist\Helper\Data $wishlistHelper,
                                \Magento\Framework\Data\Helper\PostHelper $postHelper,
                                \Magento\Review\Model\ReviewFactory $reviewFactory,
                                \Magento\Catalog\Block\Product\Compare\ListCompare $listCompare)
    {
        parent::__construct($context, $storeManager, $itemCollectionFactory, $catalogProductVisibility, $customerVisitor, $customerSession, $catalogSession, $formKey, $wishlistHelper, $postHelper);
        $this->reviewFactory = $reviewFactory;
        $this->storeManager = $storeManager;
        $this->listCompare = $listCompare;
    }
    public function getPostDataRemove($product)
    {
        $params = ['product' => $product->getId()];
        return $this->postHelper->getPostData($this->getRemoveUrl(), $params);
    }
    public function getRemoveUrl()
    {
        return $this->_getUrl('catalog/product_compare/remove');
    }
    public function getRatingSummary($product){
        $this->reviewFactory->create()->getEntitySummary($product, $this->storeManager->getStore()->getId());
        $ratingSummary = $product->getRatingSummary()->getRatingSummary();
        return $ratingSummary;
    }

    /**
     * This function is used to get compare items for customer/visitor
     * @return Collection
     */
    public function getCompareItems()
    {
        return $this->listCompare->getItems();
    }

    /**
     * This function is used to get compare product ids for customer/visitor
     * @return array
     */
    public function getCompareProductIds()
    {
        $items = $this->getCompareItems();
        $productIds = $items->getAllIds();
        return $productIds;
    }
}
