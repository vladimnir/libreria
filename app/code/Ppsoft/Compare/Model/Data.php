<?php


namespace Ppsoft\Compare\Model;

use Magento\Framework\Model\AbstractModel;

class Data extends AbstractModel
{
    protected function _construct()
    {
        $this->_init('Ppsoft\Compare\Model\ResourceModel\Data');
    }
}