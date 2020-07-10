<?php
namespace Ppsoft\Home\Plugin\Catalog\Block;

class Toolbar
{

    /**
     * Plugin
     *
     * @param \Magento\Catalog\Block\Product\ProductList\Toolbar $subject
     * @param \Closure $proceed
     * @param \Magento\Framework\Data\Collection $collection
     * @return \Magento\Catalog\Block\Product\ProductList\Toolbar
     */
    public function aroundSetCollection(
        \Magento\Catalog\Block\Product\ProductList\Toolbar $subject,
        \Closure $proceed,
        $collection
    ) {
        $currentOrder = $subject->getCurrentOrder();
        $result = $proceed($collection);

        if ($currentOrder) {
            if ($currentOrder == 'high_to_low') {
                $subject->getCollection()->setOrder('price', 'desc');
            }
            if ($currentOrder == 'low_to_high') {
                $subject->getCollection()->setOrder('price', 'asc');
            }
            if ($currentOrder == 'name_asc') {
                $subject->getCollection()->setOrder('name', 'asc');
            }
            if ($currentOrder == 'name_desc') {
                $subject->getCollection()->setOrder('price', 'desc');
            }
            if ($currentOrder == 'position_desc') {
                $subject->getCollection()->setOrder('position', 'desc');
            }
            if ($currentOrder == 'position_asc') {
                $subject->getCollection()->setOrder('position', 'asc');
            }
        }

        return $result;
    }

}