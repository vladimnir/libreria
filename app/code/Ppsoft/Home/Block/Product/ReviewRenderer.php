<?php
/**
 * Review renderer
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ppsoft\Home\Block\Product;

use Magento\Catalog\Block\Product\ReviewRendererInterface;
use Magento\Catalog\Model\Product;
use Magento\Review\Observer\PredispatchReviewObserver;

/**
 * Class ReviewRenderer
 */
class ReviewRenderer extends \Magento\Review\Block\Product\ReviewRenderer
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    public function __construct(\Magento\Framework\View\Element\Template\Context $context,
                                \Magento\Framework\Registry $registry,
                                \Magento\Review\Model\ReviewFactory $reviewFactory,
                                array $data = [])
    {
        parent::__construct($context, $reviewFactory, $data);
        $this->registry = $registry;
    }

    public function getProduct() {
        return $this->registry->registry('current_product');
    }
}
