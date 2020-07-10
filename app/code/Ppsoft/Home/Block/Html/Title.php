<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ppsoft\Home\Block\Html;


/**
 * Html page title block
 *
 * @method $this setTitleId($titleId)
 * @method $this setTitleClass($titleClass)
 * @method string getTitleId()
 * @method string getTitleClass()
 * @api
 * @since 100.0.2
 */
class Title extends \Magento\Theme\Block\Html\Title
{

    protected $_productloader;
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    public function __construct(
        \Magento\Catalog\Model\ProductFactory $_productloader,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Block\Product\Context $context
    ) {
        parent::__construct(
            $context
        );

        $this->_productloader = $_productloader;
        $this->registry = $registry;
    }
    public function loadId($id) {
        return $this->_productloader->create()->load($id);
    }
    public function getProductId()
    {
        if($this->registry->registry('product')){
            return $this->registry->registry('product')->getId();
        }

    }

}
