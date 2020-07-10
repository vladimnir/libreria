<?php

namespace Ppsoft\Home\Plugin\Catalog\Model;

class Config
{
    public function afterGetAttributeUsedForSortByArray(
        \Magento\Catalog\Model\Config $catalogConfig,
        $options
    ) {
        unset($options['position']);
        unset($options['name']);
        unset($options['price']);
        $options['low_to_high'] = __('De menor a mayor precio');
        $options['high_to_low'] = __('De mayor a menor precio');
        $options['name_asc'] = __('Por nombre de la A a la Z');
        $options['name_desc'] = __('Por nombre de la Z a la A');
        $options['position_asc'] = __('Por posición ascendentemente');
        $options['position_desc'] = __('Por posición descendentemente');
        return $options;

    }

}