<?php
/**
 * Created by PhpStorm.
 * User: oscar
 * Date: 02-12-19
 * Time: 06:18 PM
 */

namespace Ppsoft\Home\Observer\Wishlist;


use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;


class CustomerLogin implements ObserverInterface
{


    /**
     * @var \Ppsoft\Home\Helper\Wishlist\Data
     */
    private $wishlistData;

    public function __construct(\Ppsoft\Home\Helper\Wishlist\Data $wishlistData)
    {

        $this->wishlistData = $wishlistData;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $this->wishlistData->refreshFormKey();
        $this->wishlistData->calculate();
    }
}