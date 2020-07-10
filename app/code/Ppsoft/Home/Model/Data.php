<?php


namespace Ppsoft\Home\Model;

use Magento\Framework\Model\AbstractModel;

class Data extends AbstractModel
{
    protected function _construct()
    {
        $this->_init('Ppsoft\Home\Model\ResourceModel\Data');
    }
}