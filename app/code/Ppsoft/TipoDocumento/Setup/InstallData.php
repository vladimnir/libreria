<?php
namespace Ppsoft\Referencia\Setup;

use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Eav\Model\Entity\Attribute\Set as AttributeSet;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
class InstallData implements InstallDataInterface
{
    private $customerSetupFactory;

    private $attributeSetFactory;

    public function __construct(
        CustomerSetupFactory $customerSetupFactory,
        AttributeSetFactory $attributeSetFactory
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
    }
    /**
     * @param  ModuleDataSetupInterface $setup
     * @param  ModuleContextInterface   $context
     * @return void
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        // Install new attribute

        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
        $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();
        $attributeSet = $this->attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);
        $customerSetup->removeAttribute('customer', 'pos_tipodocumentoid');
        $customerSetup->addAttribute(
            'customer',
            'pos_tipodocumentoid',
            [
                'label'      => __('Tipo de Documento'),
                'type' => 'int',
                'input' => 'select',
                'source' => \Ppsoft\TipoDocumento\Model\Customer\Attribute\Source\DocumentTypeId::class,
                'required' => '1',
                'user_defined' => '1',
                'unique' => '0',
                'visible' => '1',
                'multiline_count' => '1',
                'system' => '0',
                'position' => '330',
                'is_used_in_grid' => '0',
                'is_visible_in_grid' => '0',
                'is_filterable_in_grid' => '0',
                'is_searchable_in_grid' => '0',
                'is_used_for_customer_segment' => '1',
            ]
        );
        $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'pos_tipodocumentoid')
            ->addData([
                'attribute_set_id' => $attributeSetId,
                'attribute_group_id' => $attributeGroupId,
                'used_in_forms' => ['adminhtml_customer_address',
                    'customer_address_edit',
                    'customer_register_address'
                ],
            ]);

        $attribute->save();

    }
}