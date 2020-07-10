<?php
namespace Ppsoft\Level2\Block;

class Level2 extends \Magento\Framework\View\Element\Template
{
    /**
     * @param \Magento\Framework\Registry $registry
     */

    protected $_registry;
    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    private $categoryFactory;

    public function __construct( \Magento\Framework\Registry $registry,
                                 \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Framework\View\Element\Template\Context $context,
                                 array $data = [] )
    {
        parent::__construct($context, $data);

        $this->_registry = $registry;
        $this->categoryFactory = $categoryFactory;
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

}