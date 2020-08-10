<?php
namespace Ppsoft\InstallAttributes\Setup;

use Magento\Eav\Setup\EavSetupFactory;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Eav\Model\Entity\Attribute\SetFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
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
     * Constructor
     *
     * @param EavSetupFactory $eavSetupFactory
     * @param CategorySetupFactory $categorySetupFactory
     * @param SetFactory $attributeSetFactory
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        CategorySetupFactory $categorySetupFactory,
        SetFactory $attributeSetFactory
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->categorySetupFactory = $categorySetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
    }

    public function install(
                        ModuleDataSetupInterface $setup, 
                        ModuleContextInterface $context
                    )
    {
        $setup->startSetup();
 
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);
        
        $entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);
        $attributeSetId = $categorySetup->getDefaultAttributeSetId($entityTypeId); // default attribute set
        
        $attributeSetIds = [];
 
        //create new setAttribute Electrodomesticos
        $attributeSetIds[] = $this->createNewSetAttribute('Electrodomesticos', 5, $categorySetup, $entityTypeId, $attributeSetId);
        //create new setAttribute Audio & Video
        $audioVideo = $this->createNewSetAttribute('Audio & Video', 6, $categorySetup, $entityTypeId, $attributeSetId);
        //create new setAttribute Tecnologia
        $attributeSetIds[] = $this->createNewSetAttribute('Tecnologia', 7, $categorySetup, $entityTypeId, $attributeSetId);
        //create new setAttribute Hogar
        $attributeSetIds[] = $this->createNewSetAttribute('Hogar', 8, $categorySetup, $entityTypeId, $attributeSetId);
        //create new setAttribute Hornos
        $attributeSetIds[] = $this->createNewSetAttribute('Hornos', 9, $categorySetup, $entityTypeId, $attributeSetId);
        
        //remove attribute
        $eavSetup->removeAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'color' // attribute code to remove
            );
        // add a new attribute 
        // and assign it to the "Climatizaci�n" attribute set
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'color',
            [
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'label' => 'Color',
                'input' => 'select',
                'class' => '',
                'source' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL, // can also use \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_STORE, // scope of the attribute (global, store, website)
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
                'apply_to' => '',
                //'attribute_set' => 'Climatizacion' // assigning the attribute to the attribute set "Climatizaci�n"
            ]
        );
        
        /**
         * Create a custom attribute group in all attribute sets
         * And, Add attribute to that attribute group for all attribute sets
         */
 
        // we are going to add this attribute to all attribute sets
        $attributeCode = 'color';
 
        //
        $attributeGroupName = 'Dismac';
 
        // get the attribute set ids of all the attribute sets present in your Magento store
        //$attributeSetIds = $eavSetup->getAllAttributeSetIds($entityTypeId);
 
        foreach($attributeSetIds as $attributeSetId) {
            $eavSetup->addAttributeGroup(
                $entityTypeId, 
                $attributeSetId, 
                $attributeGroupName, 
                2 // sort order
            );
            
            // get the newly create attribute group id
            $attributeGroupId = $eavSetup->getAttributeGroupId($entityTypeId, $attributeSetId, $attributeGroupName);
 
            // add attribute to group
            $categorySetup->addAttributeToGroup(
                $entityTypeId, // can also use: \Magento\Catalog\Model\Product::ENTITY instead of $entityTypeId
                $attributeSetId,
                $attributeGroupName, // attribute group
                $attributeCode, // this is defined above as 'chapagain_attribute_2
                null // sort order, can be integer value like 10 or 30, etc.
            );
        }
 
        $setup->endSetup();
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
}