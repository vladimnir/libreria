<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ppsoft\Home\Block\Form;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\AccountManagement;

/**
 * Customer edit form block
 *
 * @api
 * @SuppressWarnings(PHPMD.DepthOfInheritance)
 * @since 100.0.2
 */
class Edit extends \Magento\Customer\Block\Form\Edit
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    private $moduleManager;
    /**
     * @var string
     */
    protected $_template = 'Magento_Customer::form/newsletter.phtml';

    /**
     * Retrieve form data
     *
     * @return array
     */
    public function __construct(\Magento\Framework\Module\Manager $moduleManager, \Magento\Framework\View\Element\Template\Context $context, \Magento\Customer\Model\Session $customerSession, \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory, CustomerRepositoryInterface $customerRepository, AccountManagementInterface $customerAccountManagement, array $data = [])
    {
        parent::__construct($context, $customerSession, $subscriberFactory, $customerRepository, $customerAccountManagement, $data);
        $this->moduleManager = $moduleManager;
        $this->customerSession = $customerSession;
    }
    public function getSubscriptionObject()
    {
        if ($this->subscription === null) {
            $this->subscription =
                $this->_createSubscriber()->loadByCustomerId($this->customerSession->getCustomerId());
        }

        return $this->subscription;
    }
    /**
     * Create an instance of a subscriber.
     *
     * @return \Magento\Newsletter\Model\Subscriber
     */
    protected function _createSubscriber()
    {
        return $this->subscriberFactory->create();
    }
    /**
     * @return bool
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getIsSubscribed()
    {
        return $this->getSubscriptionObject()->isSubscribed();
    }

    /**
     * Return the save action Url.
     *
     * @return string
     */
    public function getAction()
    {
        return $this->getUrl('newsletter/manage/save');
    }

    protected function getFormData()
    {
        $data = $this->getData('form_data');
        if ($data === null) {
            $formData = $this->customerSession->getCustomerFormData(true);
            $data = [];
            if ($formData) {
                $data['data'] = $formData;
                $data['customer_data'] = 1;
            }
            $this->setData('form_data', $data);
        }
        return $data;
    }
    public function isNewsletterEnabled()
    {
        return $this->moduleManager->isOutputEnabled('Magento_Newsletter');
    }

    public function getDocumentType($documentoTypeId){
        $documentTypes = array(
            '1' => 'Pasaporte Extranjero',
            '2' => 'Carnet de Identidad');
        $documentType = $documentTypes[$documentoTypeId];
        return $documentType;
    }


}
