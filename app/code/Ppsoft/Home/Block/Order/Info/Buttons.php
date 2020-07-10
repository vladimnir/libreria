<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Block of links in Order view page
 */
namespace Ppsoft\Home\Block\Order\Info;

/**
 * @api
 * @since 100.0.2
 */
use Magento\Framework\Encryption\EncryptorInterface as Encryptor;

class Buttons extends \Magento\Sales\Block\Order\Info\Buttons
{
    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    private $directoryList;
    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    private $fileDriver;
    /**
     * @var Encryptor
     */
    private $encryptor;

    public function __construct(\Magento\Framework\View\Element\Template\Context $context,
                                \Magento\Framework\Registry $registry,
                                \Magento\Framework\App\Http\Context $httpContext,
                                \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
                                \Magento\Framework\Filesystem\Driver\File $fileDriver,
                                Encryptor $encryptor,
                                array $data = [])
    {
        parent::__construct($context, $registry, $httpContext, $data);
        $this->directoryList = $directoryList;
        $this->fileDriver = $fileDriver;
        $this->encryptor = $encryptor;

    }
    public function checkFile($data) {
        $dataEncrypt = $this->getDataEncrypted($data);
        $file = $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA) .'/facturas/' . $dataEncrypt.'.pdf';

        if ($this->fileDriver->isExists($file)) {
            return true;
        }
        return false;
    }
    public function getDataEncrypted($data)
    {
        return $this->encryptor->encrypt($data);
    }
}
