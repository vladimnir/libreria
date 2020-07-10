<?php 
namespace Ppsoft\SalesGrid\Plugin;

use Magento\Framework\Message\ManagerInterface as MessageManager;
use Magento\Sales\Model\ResourceModel\Order\Grid\Collection as SalesOrderGridCollection;
use Magento\Framework\Webapi\Rest\Request;

class SalesOrderAditionalColumns
{
    private $messageManager;
    private $collection;

    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    public function __construct(MessageManager $messageManager,
        SalesOrderGridCollection $collection,
        \Magento\Framework\Registry $registry
    ) {

        $this->messageManager = $messageManager;
        $this->collection = $collection;
        $this->registry = $registry;
    }

    public function afterGetReport(
        \Magento\Framework\View\Element\UiComponent\DataProvider\CollectionFactory $subject,
        $collection,
        $requestName
    ) {
        if ($requestName == 'sales_order_grid_data_source') {
            $select = $collection->getSelect();
            $select->joinLeft(
                    $collection->getTable("sales_order"),
                    "sales_order.entity_id = main_table.entity_id"//,
                //array('pos_nrodocumento' => 'customer_entity_varchar.value')
                )
                ->joinLeft(
                    $collection->getTable("magento_customercustomattributes_sales_flat_order_address"),
                    "sales_order.billing_address_id = magento_customercustomattributes_sales_flat_order_address.entity_id",
                    array('pos_nrodocumento' => 'magento_customercustomattributes_sales_flat_order_address.numero_documento')
                )
                ->distinct()
                ->group('main_table.entity_id');
        }
        
        return $collection;
    }
}