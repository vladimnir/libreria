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
 * @package     Mageplaza_Shopbybrand
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Ppsoft\Home\Block\Brand;

use Magento\Cms\Block\Block;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Mageplaza\Shopbybrand\Block\Brand;

/**
 * Class View
 * @package Mageplaza\Shopbybrand\Block
 */
class View extends \Mageplaza\Shopbybrand\Block\Brand\View
{

    /**
     * @return mixed
     */
    public function getMetaTitle()
    {
        $brand = $this->getBrand();
        if($brand) {
            $metaTitle = $brand->getMetaTitle();
            if ($metaTitle) {
                return $metaTitle;
            }
        }
        $title = $brand->getPageTitle() ?: $brand->getValue();

        return join($this->getTitleSeparator(), [$title, $this->getPageTitle()]);
    }

}
