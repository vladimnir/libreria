<?php
namespace Ppsoft\SalesGrid\Ui\Component\Listing\Column;

use \Magento\Sales\Api\OrderRepositoryInterface;
use \Magento\Framework\View\Element\UiComponent\ContextInterface;
use \Magento\Framework\View\Element\UiComponentFactory;
use \Magento\Ui\Component\Listing\Columns\Column;
use \Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Customer\Api\CustomerRepositoryInterface;

class DocumentNumber extends Column
{
    protected $_orderRepository;
    protected $_searchCriteria;
    
    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepositoryInterface;

    public function __construct(
                                    ContextInterface $context, 
                                    UiComponentFactory $uiComponentFactory, 
                                    OrderRepositoryInterface $orderRepository, 
                                    SearchCriteriaBuilder $criteria, 
                                    CustomerRepositoryInterface $customerRepositoryInterface,
                                    array $components = [], 
                                    array $data = [])
    {
        $this->_orderRepository = $orderRepository;
        $this->_searchCriteria  = $criteria;
        $this->customerRepositoryInterface = $customerRepositoryInterface;
        
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {

                $order  = $this->_orderRepository->get($item["entity_id"]);
                $customerId = $order->getData("customer_id");

                $documentNumber = '-';
                if ($customerId) {
                    $customer = $this->customerRepositoryInterface->getById($customerId);
                    if ($customer->getCustomAttribute('pos_nrodocumento') !== null) {
                        $documentNumber = $customer->getCustomAttribute('pos_nrodocumento')->getvalue();
                    }
                                                        
                }

                // $this->getData('name') returns the name of the column so in this case it would return export_status
                $item[$this->getData('name')] = $documentNumber;
            }
        }

        return $dataSource;
    }
}