<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * "My Wish List" link
 */
namespace Ppsoft\Home\Block;

use \Magento\Sales\Model\ResourceModel\Order\CollectionFactoryInterface;
use \Magento\Framework\App\ObjectManager;
/**
 * Class Link
 *
 * @api
 * @SuppressWarnings(PHPMD.DepthOfInheritance)
 * @since 100.0.2
 */
class Link extends \Magento\Wishlist\Block\Link
{
    protected $orderCollectionFactory;
    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\Collection
     */
    protected $orders;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;
    /**
     * @var \Magento\Sales\Model\Order\Config
     */
    protected $orderConfig;
    /**
     * @var \Magento\Framework\View\Element\Template\Context
     */
    protected $context;
    /**
     * @var \Magento\Framework\App\Http\Context
     */
    protected $httpContext;


    public function __construct(\Magento\Framework\View\Element\Template\Context $context,
                                \Magento\Wishlist\Helper\Data $wishlistHelper,
                                \Magento\Customer\Model\Session $customerSession,
                                \Magento\Framework\App\Http\Context $httpContext,
                                \Magento\Sales\Model\Order\Config $orderConfig,
                                \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
                                array $data = [])
    {
        parent::__construct($context, $wishlistHelper, $data);
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->customerSession = $customerSession;
        $this->orderConfig = $orderConfig;
        $this->context = $context;
        $this->httpContext = $httpContext;
    }

    /**
     * @return CollectionFactoryInterface
     *
     * @deprecated 100.1.1
     */
    public function getOrderCollectionFactory()
    {
        if ($this->orderCollectionFactory === null) {
            $this->orderCollectionFactory = ObjectManager::getInstance()->get(CollectionFactoryInterface::class);
        }
        return $this->orderCollectionFactory;
    }
    public function getOrders()
    {
        if (!($customerId = $this->customerSession->getCustomerId())) {
            return false;
        }
        if (!$this->orders) {
            $this->orders = $this->getOrderCollectionFactory()->create($customerId)->addFieldToSelect(
                '*'
            )->addFieldToFilter(
                'status',
                ['in' => $this->orderConfig->getVisibleOnFrontStatuses()]
            )->setOrder(
                'created_at',
                'desc'
            );
        }
        return $this->orders;
    }
    public function getCOrders()
    {
        //$this->order->getOrders()->addFieldToFilter('store_id',$this->_storeManager->getStore()->getId())->count();
        return $this->getOrders();
    }
    public function isLogged() {
        return $this->customerSession->isLoggedIn();
    }
    public function isLoggedIn()
    {
        $isLoggedIn = $this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
        return $isLoggedIn;
    }
}

