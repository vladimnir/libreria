<?php

namespace Bss\Bestsellerwidget\Block\Widget;

use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Block\Product\ListProduct;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\ProductRepository;
use Magento\Catalog\Pricing\Price\FinalPrice;
use Magento\Checkout\Helper\Cart;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\Pricing\Render;
use Magento\Reports\Helper\Data;
use Magento\Reports\Model\ResourceModel\Report\Collection\Factory;
use Magento\Sales\Model\ResourceModel\Report\Bestsellers\CollectionFactory;
use Magento\Widget\Block\BlockInterface;

/**
 * Class Bestsellerdproduct
 * @package Bss\Bestsellerwidget\Block\Widget
 */
class Bestsellerdproduct extends \Magento\Catalog\Block\Product\AbstractProduct implements BlockInterface
{
    /**
     * @var string
     */
    protected $_template = 'Bss_Bestsellerwidget::widget/bestsellerdproduct.phtml';

    /**
    * Default value for products count that will be shown
    */
    const DEFAULT_PRODUCTS_COUNT = 10;
    /**
    * Products count
    *
    * @var int
    */
    protected $productsCount;
    /**
    * @var \Magento\Framework\App\Http\Context
    */
    protected $httpContext;
    /**
     * @var CollectionFactory
     */
    protected $resourceCollection;
    /**
     * @var ProductFactory
     */
    protected $productloader;
    /**
     * @var Factory
     */
    protected $resourceFactory;
    /**
    * Catalog product visibility
    *
    * @var Visibility
    */
    protected $catalogProductVisibility;
    
    /**
    * Product collection factory
    *
    * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
    */
    protected $productCollectionFactory;
    
    /**
    * Image helper
    *
    * @var Magento\Catalog\Helper\Image
    */
    protected $imageHelper;
    /**
    * @var Cart
    */
    protected $cartHelper;
    /**
     * @var Configurable
     */
    private $configurable;
    /**
     * @var ProductRepository
     */
    private $productRepository;
    /**
     * @var ListProduct
     */
    private $listProductBlock;
    /**
     * Product types
     *
     * @var array|null
     */
    private $productTypes;

    /**
     * @param Context $context
     * @param Factory $resourceFactory
     * @param \Magento\Reports\Model\Grouped\CollectionFactory $collectionFactory
     * @param Data $reportsData
     * @param CollectionFactory $resourceCollection
     * @param ProductFactory $productloader
     * @param Configurable $configurable
     * @param ListProduct $listProductBlock
     * @param ProductRepository $productRepository
     * @param array $data
     */
   public function __construct(
        Context $context,
        Factory $resourceFactory,
        \Magento\Reports\Model\Grouped\CollectionFactory $collectionFactory,
        Data $reportsData,
        CollectionFactory $resourceCollection,
        ProductFactory $productloader,
        Configurable $configurable,
        ListProduct $listProductBlock,
        ProductRepository  $productRepository,
        array $data = []
   ) {
        $this->resourceFactory = $resourceFactory;
        $this->_collectionFactory = $collectionFactory;
        $this->_reportsData = $reportsData;
        $this->imageHelper = $context->getImageHelper();
        $this->productloader = $productloader;
        $this->cartHelper = $context->getCartHelper();
        $this->resourceCollection = $resourceCollection;
        $this->configurable = $configurable;
        $this->productRepository = $productRepository;
        parent::__construct($context, $data);
        $this->listProductBlock = $listProductBlock;
   }

    /**
     * @return ProductRepository
     */
    public function productRepositoryObj(){
        return $this->productRepository;
    }

    /**
     * @return mixed
     */
    public function isRedirectToCartEnabled()
    {
        return $this->_scopeConfig->getValue(
            'checkout/cart/redirect_to_cart',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }


    /**
     * @return Configurable
     */
    public function configurableObj(){
       return $this->configurable;
    }
        /**
    * Image helper Object
    */
     public function imageHelperObj(){
        return $this->imageHelper;
    }
    /**
    * get featured product collection
    */
   public function getBestsellerProduct(){
    $limit = $this->getProductLimit();
     $resourceCollection = $this->resourceCollection->create();
     if (count($resourceCollection)){
         $resourceCollection->setPageSize($limit);
     }
     return $resourceCollection;
   }
    /**
    * Get the configured limit of products
    * @return int
    */
    public function getProductLimit() {
     if($this->getData('productcount')==''){
         return self::DEFAULT_PRODUCTS_COUNT;
     }
        return $this->getData('productcount');
    }

    /**
    * Get the add to cart url
    * @return string
    */
    public function getAddToCartUrl($product, $additional = [])
    {
         return $this->cartHelper->getAddUrl($product, $additional);
    }

    /**
     * Return HTML block with price
     *
     * @param Product $product
     * @param string $priceType
     * @param string $renderZone
     * @param array $arguments
     * @return string
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getProductPriceHtml(
        Product $product,
        $priceType = null,
        $renderZone = Render::ZONE_ITEM_LIST,
        array $arguments = []
    ) {
        if (!isset($arguments['zone'])) {
            $arguments['zone'] = $renderZone;
        }
        $arguments['zone'] = isset($arguments['zone'])
            ? $arguments['zone']
            : $renderZone;
        $arguments['price_id'] = isset($arguments['price_id'])
            ? $arguments['price_id']
            : 'old-price-' . $product->getId() . '-' . $priceType;
        $arguments['include_container'] = isset($arguments['include_container'])
            ? $arguments['include_container']
            : true;
        $arguments['display_minimal_price'] = isset($arguments['display_minimal_price'])
            ? $arguments['display_minimal_price']
            : true;
            /** @var Render $priceRender */
        $priceRender = $this->getLayout()->getBlock('product.price.render.default');
        $price = '';
        if ($priceRender) {
            $price = $priceRender->render(
                FinalPrice::PRICE_CODE,
                $product,
                $arguments
            );
        }
        return $price;
    }

    /**
     * @param $id
     * @return Product
     */
    public function loadProduct($id)
    {
        return $this->productloader->create()->load($id);
    }

    /**
     * @param Product $product
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getProductDetailsHtml(Product $product)
    {
        $swatchBlock = $this->getLayout()->createBlock("Magento\Swatches\Block\Product\Renderer\Listing\Configurable")->setTemplate("Magento_Swatches::product/listing/renderer.phtml");
        return($swatchBlock->setProduct($product)->toHtml());
    }

    /**
     * @param $product
     * @return array
     */
    public function getAddToCartPostParams($product)
    {
        return $this->listProductBlock->getAddToCartPostParams($product);
    }

    /**
     * Returns wishlist widget options
     *
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @since 100.1.0
     */
    public function getWishlistOptions($types)
    {
        return ['productType' => $types];
    }
}