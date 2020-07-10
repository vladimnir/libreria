<?php

namespace Ppsoft\Compare\Model\ResourceModel;


use \Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Data extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('catalog_compare_item', 'catalog_compare_item_id');
    }
}