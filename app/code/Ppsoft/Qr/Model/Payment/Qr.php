<?php
declare(strict_types=1);

namespace Ppsoft\Qr\Model\Payment;

class Qr extends \Magento\Payment\Model\Method\AbstractMethod
{

    protected $_code = "qr";
    protected $_isOffline = true;

    public function isAvailable(
        \Magento\Quote\Api\Data\CartInterface $quote = null
    ) {
        return parent::isAvailable($quote);
    }
}

