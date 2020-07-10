<?php 
namespace Ppsoft\SalesGrid\Plugin;

use Magento\Framework\Data\Collection;
use Magento\Framework\Api\Filter;
use Magento\Framework\Webapi\Rest\Request;

class SalesOrderAditionalColumnsFilter
{
    private $_request;

    public function __construct(Request $request) 
    {
        $this->_request = $request;
    }

    public function aroundApply(
        \Magento\Framework\View\Element\UiComponent\DataProvider\RegularFilter $subject,
        \Closure $proceed,
        Collection $collection, 
        Filter $filter
    ) {
        if ($this->_request->getParam('namespace') == 'sales_order_grid' && $filter->getField() == 'pos_nrodocumento') {
            $filter->setField('magento_customercustomattributes_sales_flat_order_address.numero_documento');
        }
        
        return $proceed($collection, $filter);
    }
}