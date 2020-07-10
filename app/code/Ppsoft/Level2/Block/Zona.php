<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ppsoft\Level2\Block;

/**
 * Product View block
 * @api
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @since 100.0.2
 */
class Zona extends \Magento\Framework\View\Element\Template
{

    /**
     * @var \Magento\CatalogInventory\Api\StockStateInterface
     */
    protected $stockState;
    protected $stockRegistry;
    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productloader;
    /**
     * @var \Magento\Directory\Model\Currency
     */
    protected $currency;
    /**
     * @var \Magento\Catalog\Api\CategoryLinkManagementInterface
     */
    protected $categoryLinkManagementInterface;
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    private $productCollectionFactory;
    /**
     * @var \Magento\Catalog\Model\Product\Attribute\Source\Status
     */
    private $productStatus;
    /**
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    private $productVisibility;
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory
     */
    private $collecionFactory;
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    public $productRepository;

    /**
     * View constructor.
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Framework\Url\EncoderInterface $urlEncoder
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Magento\Framework\Stdlib\StringUtils $string
     * @param \Magento\Catalog\Helper\Product $productHelper
     * @param \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig
     * @param \Magento\Framework\Locale\FormatInterface $localeFormat
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     * @param \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
     * @param \Magento\CatalogInventory\Api\StockStateInterface $stockState
     * @param \Magento\Catalog\Model\ProductFactory $_productloader
     * @param \Magento\Directory\Model\Currency $currency
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\CatalogInventory\Api\StockStateInterface $stockState,
        \Magento\Catalog\Model\ProductFactory $_productloader,
        \Magento\Directory\Model\Currency $currency,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Attribute\Source\Status $productStatus,
        \Magento\Catalog\Model\Product\Visibility $productVisibility,
        \Magento\Catalog\Api\CategoryLinkManagementInterface $categoryLinkManagementInterface,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $collecionFactory,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $data
        );
        $this->stockState = $stockState;
        $this->stockRegistry = $stockRegistry;
        $this->_productloader = $_productloader;
        $this->currency = $currency;
        $this->categoryLinkManagementInterface = $categoryLinkManagementInterface;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->productStatus = $productStatus;
        $this->productVisibility = $productVisibility;
        $this->collecionFactory = $collecionFactory;
        $this->productRepository = $productRepository;
    }

    /**
     * get current currency symbol
     * @return string
     */
    public function getCurrentCurrencySymbol() {

        $symbol = $this->currency->getCurrencySymbol();

        return $symbol;
    }
    public function getProducts()
    {
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $collection */
        $collection = $this->productCollectionFactory->create()->addAttributeToSelect('*');
        $collection->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner');
        $collection->joinAttribute('visibility', 'catalog_product/visibility', 'entity_id', null, 'inner');
        $collection->addAttributeToFilter('status', ['in' => $this->productStatus->getVisibleStatusIds()])
            ->addAttributeToFilter('visibility', ['in' => $this->productVisibility->getVisibleInSiteIds()]);

        return $collection->getItems();
    }
    public function getCategory() {
        $categoryTitle = "Zona de Ahorro";
        $collection = $this->collecionFactory
            ->create()
            ->addAttributeToFilter('name',$categoryTitle)
            ->setPageSize(1);

        if ($collection->getSize()) {
            return $categoryId = $collection->getFirstItem()->getId();
        }
    }
    public function addCategory($sku, $ids) {
        try{
            $this->categoryLinkManagementInterface->assignProductToCategories(
                $sku,
                $ids
            );
        }
        catch (\Exception $e) {
            $data['error'] = $e->getMessage();
        }


    }
}
