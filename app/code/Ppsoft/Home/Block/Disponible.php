<?php
namespace Ppsoft\Home\Block;

class Disponible extends \Magento\Catalog\Block\Product\View\AbstractView
{
    /**
     * @var array
     */
    private $almacenes;
    /**
     * @var Wyomind\PointOfSale\Model\PointOfSale
     */
    private $pointOfSale;
    /**
     * @var \Wyomind\PointOfSale\Model\PointOfSaleFactory
     */
    private $posModelFactory;

    /**
     * Disponible constructor.
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Framework\Stdlib\ArrayUtils $arrayUtils
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Stdlib\ArrayUtils $arrayUtils,
        array $data = [],
        \Wyomind\PointOfSale\Model\PointOfSaleFactory $posModelFactory
    )
    {
        parent::__construct($context, $arrayUtils, $data);
        $this->posModelFactory = $posModelFactory;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getStoreCode()
    {
        return $this->_storeManager->getStore()->getCode();
    }

    /**
     * @param $data
     * @return array
     */
    public function getAlmacenes($data) {
        $stores = json_decode($data, true);
        $model  = $this->posModelFactory->create();
//        $collection = $model->getPlaces();
        $collection = $model->getCollection();
        $values = array();
        foreach ($stores as $store) {
            if (isset($store['almacenCentral']) && $store['almacenCentral'] != null) {
                continue;
            }
            $values[] = $store['almacen'];
        }
        $collection->addFieldToFilter('store_code', ['in' => $values]);
        return $collection;
    }

    /**
     * @param $data
     * @return array
     */
    public function availableAlmacenes($data) {
        $stores = json_decode($data, true);
        $model  = $this->posModelFactory->create();
        $collection = $model->getCollection();
        $values = array();
        
        foreach ($stores as $store) {
            $values[] = $store['almacen'];
        }
        
        $stockW = $collection->addFieldToFilter('store_code', ['in' => $values]);
        $almacenCentral = false;
        $countStores = 0;
        foreach ($stockW as $stock) {
            if (!$stock->getStatus()) {
                $almacenCentral = true;
            } else {
                $countStores++;
            }
        }
        
        if ( $almacenCentral && $countStores > 0) {
            return 1; //stock in sotes and alacen central
        } else {
            if ($almacenCentral) {
                return 2; // stock in almacen central
            } else {
                if ($countStores > 0) {
                    return 3; // stock in stores
                } else {
                    return 0; // no available
                }
            }
        }
    }
}
