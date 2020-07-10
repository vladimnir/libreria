<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_StoreLocator
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Ppsoft\Home\Block;


/**
 * Class Frontend
 * @package Mageplaza\StoreLocator\Block
 */
class Frontend extends \Mageplaza\StoreLocator\Block\Frontend
{


    /**
     * Get open/close time alert for each day in week
     *
     * @param $dayOpenTime
     * @param $currentTime
     *
     * @return Phrase
     */
    public function getOpenCloseTime($dayOpenTime, $currentTime)
    {
        $openTime = $this->_helperData->unserialize($dayOpenTime);
        $unit = ((float) $openTime['from'][0] > 12) ? 'PM' : 'AM';

        $fromTime = $openTime['from'][0] . ':' . $openTime['from'][1];
        $toTime = $openTime['to'][0] . ':' . $openTime['to'][1];

        if ($openTime['value']) {
            if ($currentTime >= strtotime($fromTime) && $currentTime <= strtotime($toTime)) {
                $result = __('%1 - %2 ', $fromTime, $toTime);
            } else {
                $result = __('%1 %2 ', $fromTime, $unit);
            }
        } else {
            $result = __('%1%2 ', $fromTime, $unit);
        }

        return $result;
    }

}
