<?php

namespace Ppsoft\Home\Model\ResourceModel;


use \Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Data extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('report_viewed_product_index', 'index_id');
    }
}