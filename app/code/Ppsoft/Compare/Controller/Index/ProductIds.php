<?php

namespace Ppsoft\Compare\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\View\Result\PageFactory;
use Ppsoft\Compare\Helper\Product\Compare;

class ProductIds extends Action
{

    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @var JsonFactory
     */
    protected $_resultJsonFactory;
    /**
     * @var Compare
     */
    private $data;

    /**
     * Speakers constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param JsonFactory $resultJsonFactory
     * @param Compare $data
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory,
        \Ppsoft\Compare\Helper\Product\Compare $data
    ) {
        $this->_resultPageFactory = $resultPageFactory;
        $this->_resultJsonFactory = $resultJsonFactory;

        parent::__construct($context);
        $this->data = $data;
    }

    /**
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $result = $this->_resultJsonFactory->create();
        $request = $this->getRequest()->isXmlHttpRequest();

        if ($request) {
            $productIds = $this->data->getCompareProductIds();
            $result->setData(['productIds' => $productIds]);
        }

        return $result;
    }
 }