<?php
namespace Ppsoft\Home\Block;


use Magento\Catalog\Block\Product\Context;
use Magento\Framework\Data\Helper\PostHelper;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\Url\Helper\Data;
use Magento\Catalog\Helper\Product\Compare;


class ListProduct extends \Magento\Catalog\Block\Product\ListProduct
{

    /**
     * @var compareProductHelper
     */
    protected $compareProductHelper;
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
     * @var PostHelper
     */
    private $postDataHelper;
    /**
     * @var Resolver
     */
    private $layerResolver;
    /**
     * @var \Magento\Catalog\CustomerData\CompareProducts
     */
    protected $compareProducts;
    protected $session;
    protected $resourceConnection;
    /**
     * @var \Ppsoft\Compare\Model\Data
     */
    protected $model;

    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    /**
     * ListProduct constructor.
     * @param Context $context
     * @param PostHelper $postDataHelper
     * @param Resolver $layerResolver
     * @param CategoryRepositoryInterface $categoryRepository
     * @param Data $urlHelper
     * @param \Magento\Review\Model\ReviewFactory $reviewFactory
     * @param \Magento\Directory\Model\Currency $currency
     */
    public function __construct(Context $context, PostHelper $postDataHelper,
                                Resolver $layerResolver,
                                CategoryRepositoryInterface $categoryRepository,
                                Data $urlHelper,
                                \Ppsoft\Compare\Model\Data $model,
                                \Magento\Framework\Registry $registry,
                                \Magento\Review\Model\ReviewFactory $reviewFactory,
                                Compare $compareProductHelper,
                                \Magento\Framework\App\ResourceConnection $resourceConnection,
                                \Magento\Framework\Session\SessionManagerInterface $session,
                                \Magento\Catalog\Block\Product\Compare\ListCompare $compareProducts,
                                \Magento\Directory\Model\Currency $currency
                                )
    {
        parent::__construct($context, $postDataHelper, $layerResolver,$categoryRepository, $urlHelper);

        $this->reviewFactory = $reviewFactory;
        $this->currency = $currency;
        $this->compareProductHelper = $compareProductHelper;
        $this->compareProducts = $compareProducts;
        $this->session = $session;
        $this->model = $model;
        $this->resourceConnection = $resourceConnection;
        $this->registry = $registry;
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
    public function getCompare($product)
    {
        return $this->compareProductHelper->getPostDataParams($product);
    }
    public function getCompareCollection()
    {
        return $this->compareProductHelper->getItemCollection();
    }
    public function getCompareList(){
        return $this->compareProducts->getItems();
    }
    public function getVisitor()
    {
       return $this->session->getVisitorData();
    }
    public function getVisitorCollection()
    {
        return $this->model->getCollection();
    }
    public function deleteProduct($productId) {
        $table = $this->resourceConnection->getTableName("catalog_compare_item");
        $this->resourceConnection->getConnection()->delete($table,["product_id = $productId"]);
    }
    public function getCurrentCategory()
    {
        if($this->registry->registry('current_category')) {
            return $this->registry->registry('current_category');
        }
        else {
            return '';
        }
    }
}