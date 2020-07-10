<?php


namespace Ppsoft\Home\Model\ResourceModel\Data;


use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;


class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            'Ppsoft\Home\Model\Data',
            'Ppsoft\Home\Model\ResourceModel\Data'
        );
    }
}