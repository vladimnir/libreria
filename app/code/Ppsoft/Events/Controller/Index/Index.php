<?php
namespace Ppsoft\Events\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{
    protected $formKey;
    protected $cart;
    protected $product;
    protected $resultRedirect;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Data\Form\FormKey $formKey,
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Catalog\Model\Product $product,
        \Magento\Framework\Controller\ResultFactory $result,
        array $data = []) {
        $this->formKey = $formKey;
        $this->cart = $cart;
        $this->product = $product;
        $this->resultRedirect = $result;
        parent::__construct($context);
    }

    public function execute()
    {
        $productId = $this->getRequest()->getParam('product_id');

        $resultRedirect = $this->resultRedirect->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
        
        try {
            $_product = $this->product->load($productId);
            
            $product_type = $_product->getTypeID();
            $stock = $_product->getExtensionAttributes()->getStockItem()->getQty();
            $cartitems = $this->cart->getQuote()->getAllItems();
            $cartqty = array();
            $ids = array();
                foreach ($cartitems as $item) {
                    $cartqty[$productId] = $item->getQty();
                    $ids[]= $item->getProductId();
                }
            $qtytotal = 1;
    
            if (in_array($productId, $ids)) {
                    $qtytotal = $stock - $cartqty[$productId];
            }
    
            if($product_type == 'configurable') {
                $attributes = $_product->getTypeInstance(true)->getConfigurableAttributesAsArray($_product);
                    $super_attributes = array();
                    foreach ($attributes as $attribute) {
                        foreach ($attribute['values'] as $value)  {
                            $super_attributes[$attribute['attribute_id']] = $value['value_index'];
                        }
                    }
                
                $resultRedirect->setPath('catalog/product/view/id/'.$productId);
                if($qtytotal > 0) {
                    $paramsC = array(
                        'form_key' => $this->formKey->getFormKey(),
                        'product' => $productId,
                        'super_attribute' => $super_attributes,
                        'qty'   =>1
                    );
                    $this->cart->addProduct($_product, $paramsC);
                    $this->cart->save();
                    $resultRedirect->setPath('checkout/cart/', ['_current' => true]);
                }
    
    
    
                return $resultRedirect;
            }
            
            $resultRedirect->setPath('catalog/product/view/id/'.$productId);
            if($qtytotal > 0) {
                $params = array(
                    'form_key' => $this->formKey->getFormKey(),
                    'product' => $productId,
                    'qty'   => 1
                );

                if ($product_type == 'bundle') {
                    $productsArray = $this->getBundleOptions($_product);
                    $params = [
                        'form_key' => $this->formKey->getFormKey(),
                        'product' => $productId,
                        'bundle_option' => $productsArray,
                        'qty' => 1
                    ];

                }

                $this->cart->addProduct($_product, $params);
                $this->cart->save();
                $resultRedirect->setPath('checkout/cart/', ['_current' => true]);
    
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $resultRedirect->setPath('catalog/product/view/id/'.$productId);
            return $resultRedirect;
        }

        return $resultRedirect;
    }

    /**
     * get all the selection products used in bundle product
     * @param $product
     * @return mixed
     */
    private function getBundleOptions(\Magento\Catalog\Model\Product $product)
    {
        $selectionCollection = $product->getTypeInstance()
            ->getSelectionsCollection(
                $product->getTypeInstance()->getOptionsIds($product),
                $product
            );
        $bundleOptions = [];
        foreach ($selectionCollection as $selection) {
            $bundleOptions[$selection->getOptionId()][] = $selection->getSelectionId();
        }
        return $bundleOptions;
    }
}