<?php
namespace Ppsoft\CustomMenu\Block;

class Level2 extends \Magento\Framework\View\Element\Template
{
    /**
     * @param \Magento\Framework\Registry $registry
     */

    protected $_registry;
    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $categoryFactory;
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory
     */
    protected $categoryCollectionFactory;
    /**
     * @var \Magento\Catalog\Helper\Category
     */
    protected $categoryHelper;

    public function __construct( \Magento\Framework\Registry $registry,
                                 \Magento\Catalog\Model\CategoryFactory $categoryFactory,
                                 \Magento\Catalog\Helper\Category $categoryHelper,
                                 \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Framework\View\Element\Template\Context $context,
                                 array $data = [] )
    {
        parent::__construct($context, $data);

        $this->_registry = $registry;
        $this->categoryFactory = $categoryFactory;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->categoryHelper = $categoryHelper;
    }

    public function getCategoryChilds()
    {
         $catId = $this->_registry->registry('current_category')->getId();
         $category = $this->categoryFactory->create()->load($catId);
         return $category->getChildrenCategories();
    }
    public function getCategoryGrandChilds($id)
    {
        $category = $this->categoryFactory->create()->load($id);
        return $category->getChildrenCategories();
    }
    public function getCategoryParent()
    {
        $catId = $this->_registry->registry('current_category')->getId();
        $category = $this->categoryFactory->create()->load($catId);
        return $category->getParentCategory();
    }
    public function getCategoryParentChilds()
    {
        $catId = $this->_registry->registry('current_category')->getId();
        $category = $this->categoryFactory->create()->load($catId);
        return $category->getParentCategory()->getChildrenCategories();
    }
    public function getCurrentCategory()
    {
        return $this->_registry->registry('current_category');
    }
    public function getLoadedCategory($id)
    {
        return $this->categoryFactory->create()->load($id);
    }
    public function getCategoryCollection($isActive = true, $level = false, $sortBy = false, $pageSize = false, $entity = true)
    {
        $collection = $this->categoryCollectionFactory->create();
        $collection->addAttributeToSelect('*');

        // select only active categories
        if ($isActive) {
            $collection->addIsActiveFilter();
        }

        // select categories of certain level
        if ($level) {
            $collection->addLevelFilter($level);
        }

        // sort categories by some value
        if ($sortBy) {
            $collection->addOrderField($sortBy);
        }

        // select certain number of categories
        if ($pageSize) {
            $collection->setPageSize($pageSize);
        }
        // select certain id
        if ($entity) {
            $collection->addAttributeToFilter('entity_id', array('nin' => 2));
        }
        // categories only incluede in the meni
        if ($entity) {
            $collection->addAttributeToFilter('include_in_menu', 1);
        }
        return $collection->setOrder('position','ASC');
    }

}