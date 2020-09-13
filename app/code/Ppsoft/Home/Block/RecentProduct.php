<?php
namespace Ppsoft\Home\Block;


use Magento\Framework\View\Element\Template\Context;
use Ppsoft\Home\Model\DataFactory;
use Magento\Catalog\Model\ProductFactory;


class RecentProduct extends \Magento\Framework\View\Element\Template
{

    /**
     * @var ProductFactory
     */
    protected $_productloader;
    /**
     * @var \Magento\Review\Model\ReviewFactory
     */
    protected $reviewFactory;
    /**
     * @var \Magento\Directory\Model\Currency
     */
    protected $currency;
    /**
     * @var \Magento\Catalog\Block\Product\ListProduct
     */
    protected $listProductBlock;
    /**
     * @param \Magento\Framework\Registry $registry
     */

    protected $_registry;
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;
    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $categoryFactory;
    /**
     * @var \Mageplaza\Shopbybrand\Helper\Data
     */
    protected $mageplaza;
    
    /**
     * @var \Wyomind\PointOfSale\Model\PointOfSaleFactory
     */
    private $posModelFactory;

    public function __construct(
                                    \Magento\Framework\Registry $registry,
                                    Context $context,
                                    DataFactory $modelFactory,
                                    ProductFactory $_productloader,
                                    \Mageplaza\Shopbybrand\Helper\Data $mageplaza,
                                    \Magento\Catalog\Model\CategoryFactory $categoryFactory,
                                    \Magento\Catalog\Block\Product\ListProductFactory $listProductBlock,
                                    \Magento\Review\Model\ReviewFactory $reviewFactory,
                                    \Magento\Framework\Stdlib\DateTime\DateTime $date,
                                    \Magento\Directory\Model\Currency $currency
                                )
    {
        $this->modelFactory = $modelFactory;
        $this->_productloader = $_productloader;
        $this->reviewFactory = $reviewFactory;
        $this->currency = $currency;
        $this->listProductBlock = $listProductBlock;
        $this->_registry = $registry;
        $this->date = $date;
        $this->categoryFactory = $categoryFactory;
        $this->mageplaza = $mageplaza;
        
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
     */
    public function getProductCollection()
    {
        $productCollection = $this->modelFactory->create()->getCollection();
        return $productCollection;
    }

    /**
     * @param $id
     * @return $this
     */
    public function getLoadProduct($id)
    {
        $loaded = $this->_productloader->create()->load($id);
        return $loaded;
    }
    public function getRatingSummary($product)
    {
        $this->reviewFactory->create()->getEntitySummary($product, $this->_storeManager->getStore()->getId());
        $ratingSummary = $product->getRatingSummary()->getRatingSummary();
        return $ratingSummary;
    }
    public function getCurrentCurrencySymbol()
    {
        return $this->currency->getCurrencySymbol();
    }
    public function getAddToCartPostParams($product)
    {
        return $this->listProductBlock->create()->getAddToCartPostParams($product);
    }
    public function getCurrentCategory()
    {
        return $category = $this->_registry->registry('current_category');
    }
    public function getTimestamp() {
       return $this->date->timestamp();
    }
    public function getCategoryChilds()
    {
        $catId = $this->_registry->registry('current_category')->getId();
        $category = $this->categoryFactory->create()->load($catId);
        return $category->getChildrenCategories();
    }
    public function getMarcaDescription() {
       return $this->mageplaza->getBrandDescription($this->mageplaza->getBrand(), $short = false);
    }
    public function getMarca() {
       return $this->mageplaza->getBrand();
    }


}