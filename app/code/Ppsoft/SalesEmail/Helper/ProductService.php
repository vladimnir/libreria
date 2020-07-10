<?php
/**
 * Created by PhpStorm.
 * User: oscar
 * Date: 13-11-19
 * Time: 10:24 AM
 */

namespace Ppsoft\SalesEmail\Helper;


class ProductService extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    private $productLoader;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var \Magento\Catalog\Model\Product
     */
    private $productModel;

    public function __construct(\Magento\Catalog\Model\ProductFactory $productLoader,
                                \Magento\Store\Model\StoreManagerInterface $storeManager,
                                \Magento\Catalog\Model\Product $productModel)
    {
        $this->productLoader = $productLoader;
        $this->storeManager = $storeManager;
        $this->productModel = $productModel;
    }

    public function getService($productId){
        $res = array();
        $product = $this->productLoader->create()->load($productId);
        $installation = $product->getResource()->getAttribute('codigo_instalacion')->getFrontend()->getValue($product);
        $isService = '';
        if (!empty($product->getResource()->getAttribute('clasificacion')->getFrontend()->getValue($product))) {
            $isService = json_decode($product->getResource()    ->getAttribute('clasificacion')->getFrontend()->getValue($product))->clasificacion->codigo;
        }
        if ($installation != null && $isService != 133){
            $res = $this->getPdfService($installation, $productId);
        }
        return $res;
    }

    public function getStoreBaseUrl(){
        return $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }

    public function getPdfService($installCode, $productId){
        $file = "";
        $name = $this->productModel->load($productId)->getName();
        $data = array();
        switch ($installCode){
            case 'INST-AAC01':
                $file = 'aire-acondicionado.pdf';
                break;
            case 'INST-CAL01':
                $file = 'calefones.pdf';
                break;
            case 'INST-CAM01':
                $file = 'campanas2.pdf';
                break;
            case 'INST-LAV01':
                $file = 'lavaropas.pdf';
                break;
            case 'INST-COC01':
                $file = 'cocina.pdf';
                break;
            case 'INST-SBS01':
                $file = 'refrigeradores.pdf';
                break;
            case 'INST-LAV01':
                $file = 'secaropas.pdf';
                break;
            case 'INST-TVV01':
                $file = 'televisores.pdf';
                break;
            case 'INST-CAL02':
                $file = 'termotanques.pdf';
                break;
        }
        $data = array( 'file' => $file, 'product_name' => $name);
        return $data;
    }
}