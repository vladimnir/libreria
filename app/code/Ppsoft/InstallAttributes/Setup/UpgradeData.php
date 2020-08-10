<?php

namespace Wagento\InstallAttributes\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Eav\Model\Entity\Attribute\SetFactory;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory;
use Wagento\InstallAttributes\Helper\Data as InstallerHelper;

/**
 * @codeCoverageIgnore
 */
class UpgradeData implements UpgradeDataInterface
{
    /**
     * EAV setup factory
     *
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    private $eavSetupFactory;
 
    /**
     * Category setup factory
     *
     * @var CategorySetupFactory
     */
    private $categorySetupFactory;
 
    /**
     * Attribute set factory
     *
     * @var SetFactory
     */
    private $attributeSetFactory;
    
    /**
     * Attribute Collection Factory
     *
     * @var CollectionFactory
     */
    protected $_attributeSetCollection;
    
    /**
     * @var InstallerHelper
     */
    protected $helper;
 
    /**
     * Constructor
     *
     * @param EavSetupFactory $eavSetupFactory
     * @param CategorySetupFactory $categorySetupFactory
     * @param SetFactory $attributeSetFactory
     * @param CollectionFactory $attributeSetCollection
     * @param InstallerHelper $helper
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        CategorySetupFactory $categorySetupFactory,
        SetFactory $attributeSetFactory,
        CollectionFactory $attributeSetCollection,
        InstallerHelper $helper
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->categorySetupFactory = $categorySetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->_attributeSetCollection = $attributeSetCollection;
        $this->helper = $helper;
    }

    public function upgrade(
                        ModuleDataSetupInterface $setup, 
                        ModuleContextInterface $context
                    )
    {
        $setup->startSetup();

        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);
        
        $entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);
        $attributeSetId = $categorySetup->getDefaultAttributeSetId($entityTypeId); // default attribute set


        
        if (version_compare($context->getVersion(), '0.0.4') < 0) {
            $optionsArray = $this->helper->getAttributeOptions();
            foreach ($optionsArray as $cod => $values) {
                $attributeId = $eavSetup->getAttributeId('catalog_product', $cod);
                $options = [
                        'values' => $values['values'],
                        'attribute_id' => $attributeId,
                ];
                
                $eavSetup->addAttributeOption($options);
            }
        }
        
        if (version_compare($context->getVersion(), '0.0.5') < 0) {
            foreach ($this->helper->getAttributes() as $attr) {
                $eavSetup->updateAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    $attr['code'], // attribute code
                    [
                        'is_visible_on_front' => true
                        // field name => value to update
                    ]
                );
            }
        }
        
        if (version_compare($context->getVersion(), '0.0.6') < 0) {
            $this->createAttributeTextArea('clasificacion', 'clasificacion', $eavSetup);
        }


        
        if (version_compare($context->getVersion(), '0.0.9') < 0) {
            foreach ($this->helper->getAttributesPost() as $attr) {
                $eavSetup->updateAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    $attr['code'], // attribute code
                    [
                        'is_global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE
                    ]
                );
            }
        }


        
        if (version_compare($context->getVersion(), '0.1.3') < 0) {
            $eavSetup->updateAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'marca', // attribute code
                [
                    'backend_type' => 'int',
                    'frontend_input' => 'select',
                    'is_filterable' => 1,
                    'is_filterable_in_search' => 1
                ]
            );
            
            $optionsArray = $this->helper->getOptionsMarca();
            $attributeId = $eavSetup->getAttributeId('catalog_product', 'marca');
            //foreach ($optionsArray as $option) {
                $options = [
                        'values' => $optionsArray,
                        'attribute_id' => $attributeId,
                ];
                
                $eavSetup->addAttributeOption($options);
            //}
        }

        if (version_compare($context->getVersion(), '0.1.4') < 0) {
            $eavSetup->updateAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'ficha_tecnica', // attribute code
                [
                    'is_wysiwyg_enabled' => 1,
                    'is_html_allowed_on_front' => 1
                ]
            );
        }

        if (version_compare($context->getVersion(), '0.1.5') < 0) {
            $eavSetup->updateAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'status', // attribute code
                [
                    'is_global' => 0,
                ]
            );
        }
            
        $setup->endSetup();
    }
    
    /**
     *
     * @param string $attributeSetName
     * @return int attributeSetId
     */
    public function getAttributeSetId($attributeSetName)
    {
        $attributeSetCollection = $this->_attributeSetCollection->create()
          ->addFieldToSelect('attribute_set_id')
          ->addFieldToFilter('attribute_set_name', $attributeSetName)
          ->getFirstItem()
          ->toArray();
        
        $attributeSetId = (int) $attributeSetCollection['attribute_set_id'];
        // OR (see benchmark below for make your choice)
        $attributeSetId = (int) implode($attributeSetCollection);
        
        return $attributeSetId;
    }
    
    private function createNewSetAttribute($attributeSetName, $sortOrder, $categorySetup, $entityTypeId, $attributeSetId)
    {
        /**
         * Create a New Attribute Set
         */
        $attributeSet = $this->attributeSetFactory->create();
        
        $data = [
                'attribute_set_name' => $attributeSetName,
                'entity_type_id' => $entityTypeId,
                'sort_order' => $sortOrder,
            ];
            
        $attributeSet->setData($data);
        $attributeSet->validate();
        $attributeSet->save();
        $attributeSet->initFromSkeleton($attributeSetId);
        $attributeSet->save();
        
        return $attributeSet->getId();
    }
    
    private function createAttributeSelect($code, $name, $eavSetup)
    {
        //remove attribute
        $eavSetup->removeAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                $code // attribute code to remove
            );
        // add a new attribute 
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            $code,
            [
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'label' => $name,
                'input' => 'select',
                'class' => '',
                'source' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => ''
            ]
        );
    }
    
    private function createAttributeText($code, $name, $eavSetup)
    {
        //remove attribute
        $eavSetup->removeAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                $code // attribute code to remove
            );
        // add a new attribute 
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            $code,
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => $name,
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => ''
            ]
        );
    }
    
    //textarea
    private function createAttributeTextArea($code, $name, $eavSetup)
    {
        //remove attribute
        $eavSetup->removeAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                $code // attribute code to remove
            );
        // add a new attribute 
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            $code,
            [
                'type' => 'text',
                'backend' => '',
                'frontend' => '',
                'label' => $name,
                'input' => 'textarea',
                'class' => '',
                'source' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => ''
            ]
        );
    }
    
    private function createAttributeMultiSelect($code, $name, $eavSetup)
    {
        //remove attribute
        $eavSetup->removeAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                $code // attribute code to remove
            );
        // add a new attribute 
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            $code,
            [
                'type' => 'varchar',
                'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                'frontend' => '',
                'label' => $name,
                'input' => 'multiselect',
                'class' => '',
                'source' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => ''
            ]
        );
    }
    
    private function createAttributePrice($code, $name, $eavSetup)
    {
        //remove attribute
        $eavSetup->removeAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                $code // attribute code to remove
            );
        // add a new attribute 
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            $code,
            [
                'type' => 'decimal',
                'backend' => 'Magento\Catalog\Model\Product\Attribute\Backend\Price',
                'frontend' => '',
                'label' => $name,
                'input' => 'price',
                'class' => '',
                'source' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => ''
            ]
        );
    }
}