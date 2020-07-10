<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Ppsoft\Home\Block\Address;

use \Magento\Framework\App\ObjectManager;
use \Magento\Sales\Model\ResourceModel\Order\CollectionFactoryInterface;

/**
 * Sales order history block
 *
 * @api
 * @since 100.0.2
 */
class Edit extends \Magento\Customer\Block\Address\Edit
{

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var \Magento\Directory\Model\Currency
     */
    protected $currency;
    /**
     * @var \Magento\Customer\Api\AddressRepositoryInterface
     */
    protected $addressRepository;
    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $customerRepository;

   public function __construct(
                            \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository, 
                            \Magento\Directory\Model\Currency $currency,
                            \Magento\Framework\View\Element\Template\Context $context, 
                            \Magento\Directory\Helper\Data $directoryHelper, 
                            \Magento\Framework\Json\EncoderInterface $jsonEncoder, 
                            \Magento\Framework\App\Cache\Type\Config $configCacheType, 
                            \Magento\Directory\Model\ResourceModel\Region\CollectionFactory $regionCollectionFactory, 
                            \Magento\Directory\Model\ResourceModel\Country\CollectionFactory $countryCollectionFactory, 
                            \Magento\Customer\Model\Session $customerSession, 
                            \Magento\Customer\Api\AddressRepositoryInterface $addressRepository, 
                            \Magento\Customer\Api\Data\AddressInterfaceFactory $addressDataFactory, 
                            \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer, 
                            \Magento\Framework\Api\DataObjectHelper $dataObjectHelper, 
                            array $data = [])
   {
       parent::__construct($context, $directoryHelper, $jsonEncoder, $configCacheType, $regionCollectionFactory, $countryCollectionFactory, $customerSession, $addressRepository, $addressDataFactory, $currentCustomer, $dataObjectHelper, $data);
       $this->currency = $currency;
       $this->addressRepository = $addressRepository;
       $this->customerRepository = $customerRepository;
       $this->_customerSession = $customerSession;
   }

    public function getCurrentCurrencySymbol()
    {
        return $this->currency->getCurrencySymbol();
    }

    public function getMyBillingAddress()
    {
        $customer = $this->customerRepository->getById($this->_customerSession->getId());
        $billingAddressId = $customer->getDefaultBilling();
        return $this->addressRepository->getById($billingAddressId);
    }

    public function getStoreName()
    {
        try {
            $id = $this->_storeManager->getStore()->getId();
        } catch (\Exception $exception) {
        }
        $stores = [
            1 => 'La Paz',
            2 => 'Santa Cruz',
            3 => 'Cochabamba',
        ];
        return $stores[$id];
    }

    public function getAttributeValue($attrCode)
    {
        $id = $this->getRequest()->getParam('id');
        if ($id == null) return '';
        try {
            /** @var \Magento\Customer\Api\Data\AddressInterface $addresDataModel */
            $addresDataModel = $this->addressRepository->getById($id);
            $value = $addresDataModel->getCustomAttribute($attrCode) != null ? $addresDataModel->getCustomAttribute($attrCode)->getValue() : '';
            return $value;
        } catch (\Exception $exception) {
        }
        return '';
    }
}
