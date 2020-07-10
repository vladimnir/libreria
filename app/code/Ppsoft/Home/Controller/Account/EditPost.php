<?php

namespace Ppsoft\Home\Controller\Account;

class EditPost extends \Magento\Customer\Controller\Account\EditPost
{
    public function execute()
    {
        $this->fillMissingData();
        return parent::execute();
    }

    public function fillMissingData($currentCustomerDataObject = null)
    {
        // this can be replaced
        $customerId = $this->session->getCustomerId();
        $currentCustomerDataObject = $this->customerRepository->getById($customerId);
        // this can be replaced

        $whitelist = [
            'pos_tipodocumentoid',
            'pos_has_minicutoas',
            'pos_clevernrotarjeta',
            'pos_nrodocumento',
            'pos_clevercelular',
            'pos_id',
            'pos_cleverid',
            'pos_sapid',
            'pos_contact_id',
            'dismac_club_points'
        ];
        $customerAttrList = $currentCustomerDataObject->getCustomAttributes();

        foreach ($customerAttrList as $attr) {
            if($postParam = $this->_request->getParam($attr->getAttributeCode())) continue;
            $this->_request->setParam($attr->getAttributeCode(), $attr->getValue());
        }
    }
}