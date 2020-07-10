<?php
/**
 * Created by PhpStorm.
 * User: oscar
 * Date: 02-12-19
 * Time: 05:56 PM
 */

namespace Ppsoft\Home\Helper\Wishlist;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\App\PageCache\FormKey as PageCacheFormKey;
use Magento\Framework\Session\Config\ConfigInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;

class Data extends \Magento\Wishlist\Helper\Data
{
    public function refreshFormKey(){
        $beforeWishlistRequest = $this->_customerSession->getBeforeWishlistRequest();

        if ($beforeWishlistRequest !== null) {

            $formKeyData = ObjectManager::getInstance()->get(FormKey::class);

            //form_key is already empty
            $formKey = $formKeyData->getFormKey();

            $sessionConfig = ObjectManager::getInstance()->get(ConfigInterface::class);
            $cookieMetadata = ObjectManager::getInstance()->get(CookieMetadataFactory::class)->createPublicCookieMetadata();
            $cookieMetadata->setDomain($sessionConfig->getCookieDomain());
            $cookieMetadata->setPath($sessionConfig->getCookiePath());
            $cookieMetadata->setDuration($sessionConfig->getCookieLifetime());

            ObjectManager::getInstance()->get(PageCacheFormKey::class)->set(
                $formKey,
                $cookieMetadata
            );

            $beforeWishlistRequest['form_key'] = $formKey;
            $this->_customerSession->setBeforeWishlistRequest($beforeWishlistRequest);
            $this->_customerSession->setBeforeRequestParams($beforeWishlistRequest);
            $this->_customerSession->setBeforeModuleName('wishlist');
            $this->_customerSession->setBeforeControllerName('index');
            $this->_customerSession->setBeforeAction('add');
        }

        return $this;
    }
}