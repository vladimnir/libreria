<?php


namespace Ppsoft\Compare\Model\ResourceModel\Data;


use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;


class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            'Ppsoft\Compare\Model\Data',
            'Ppsoft\Compare\Model\ResourceModel\Data'
        );
    }
}