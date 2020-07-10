<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ppsoft\Home\Block\Order;

use \Magento\Framework\App\ObjectManager;
use \Magento\Sales\Model\ResourceModel\Order\CollectionFactoryInterface;
use Magento\Framework\Encryption\EncryptorInterface as Encryptor;

/**
 * Sales order history block
 *
 * @api
 * @since 100.0.2
 */
class History extends \Magento\Sales\Block\Order\History
{
    /**
     * @var string
     */
    protected $_template = 'Magento_Sales::order/history.phtml';

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    protected $_orderCollectionFactory;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var \Magento\Sales\Model\Order\Config
     */
    protected $_orderConfig;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\Collection
     */
    protected $orders;

    /**
     * @var CollectionFactoryInterface
     */
    private $orderCollectionFactory;
    /**
     * @var \Magento\Directory\Model\Currency
     */
    protected $currency;
    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    private $fileDriver;
    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    private $directoryList;
    /**
     * @var Encryptor
     */
    private $encryptor;
    protected $logger;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Sales\Model\Order\Config $orderConfig
     * @param array $data
     */
   public function __construct(\Magento\Directory\Model\Currency $currency,
                               \Magento\Framework\Filesystem\Driver\File $fileDriver,
                               \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
                               \Magento\Framework\View\Element\Template\Context $context,
                               \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
                               Encryptor $encryptor,
                               \Psr\Log\LoggerInterface $logger,
                               \Magento\Customer\Model\Session $customerSession, \Magento\Sales\Model\Order\Config $orderConfig,
                               array $data = [])
   {
       parent::__construct($context, $orderCollectionFactory, $customerSession, $orderConfig, $data);
       $this->currency = $currency;
       $this->fileDriver = $fileDriver;
       $this->directoryList = $directoryList;
       $this->encryptor = $encryptor;
       $this->logger = $logger;
   }


    public function getCurrentCurrencySymbol()
    {
        return $this->currency->getCurrencySymbol();
    }

    public function checkFile($data) {
        $dataEncrypt = md5($data);
       $file = $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA) .'/facturas/' . $dataEncrypt.'.pdf';

       if ($this->fileDriver->isExists($file)) {
           return $dataEncrypt;
       }
       return null;
    }
    public function getCustomerId()
    {
        return $this->_customerSession->getCustomer()->getId();
    }
    public function getDataEncrypted($data)
    {
        return md5($data);
    }
    public function logger($message)
    {
        return $this->logger->info($message);
    }
}
