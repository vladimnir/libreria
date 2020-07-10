<?php

namespace Ppsoft\InstallConfig\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Registry;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Config;
use Magento\Customer\Model\Customer;

/**
 * @codeCoverageIgnore
 */
class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var \Magento\Cms\Model\PageFactory
     */
    protected $_blockFactory;
    
    /**
     * @var \Magento\Framework\App\Config\ConfigResource\ConfigInterface
     */
    protected $resourceConfig;
    
    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $categoryFactory;
    
    /**
     * @var Registry
     */
    private $registry;
    
    /**
     * @var \Magento\Catalog\Model\Category
     */
    protected $categoryModel;
    
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManagerInterface;
    
    /**
     * EAV setup factory
     *
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * Construct
     *
     * @param \Magento\Cms\Model\BlockFactory $blockFactory
     */
    public function __construct(
        \Magento\Cms\Model\BlockFactory $blockFactory,
        \Magento\Framework\App\Config\ConfigResource\ConfigInterface  $resourceConfig,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        Registry $registry,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollection,
        \Magento\Catalog\Model\Category $categoryModel,
        \Magento\Store\Model\StoreManager $storeManagerInterface,
        \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory,
        Config $eavConfig
    )
    {
        $this->_blockFactory = $blockFactory;
        $this->resourceConfig = $resourceConfig;
        $this->categoryFactory = $categoryFactory;
        $this->registry = $registry;
        $this->categoryCollection = $categoryCollection;
        $this->categoryModel = $categoryModel;
        $this->storeManagerInterface = $storeManagerInterface;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->eavConfig       = $eavConfig;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        if (version_compare($context->getVersion(), '0.0.2') < 0) {
            $this->changeConfigData('carriers/pickupatstore/settings/display_gmap', 1);
        }
        
        if (version_compare($context->getVersion(), '0.0.3') < 0) {
            $this->changeConfigData('trans_email/ident_general/name', 'Dismac');
            $this->changeConfigData('trans_email/ident_general/email', 'boletin@dismac.com.bo');
            
            $this->changeConfigData('trans_email/ident_support/name', 'Soporte Dismac');
            //$this->changeConfigData('trans_email/ident_support/email', 'Soporte Dismac');
        }
        
        if (version_compare($context->getVersion(), '0.0.4') < 0) {
            $this->changeConfigData('trans_email/ident_support/email', 'atencionalcliente@dismac.com.bo');
            
            $this->changeConfigData('trans_email/ident_sales/name', 'Dismac');
            $this->changeConfigData('trans_email/ident_sales/email', 'ecommerce@dismac.com.bo');
        }
        if (version_compare($context->getVersion(), '0.0.5', '<')){
            $this->changeConfigData('Ppsoft_emails/offline_payments_emails/enabled', '0');
        }
        
        if (version_compare($context->getVersion(), '0.0.6', '<')){
            $eavSetup->updateAttribute(
                \Magento\Catalog\Model\Category::ENTITY,
                'url_key', // attribute code
                [
                    'is_global' => 1,
                ]
            );
        }

        $setup->endSetup();
    }
    
    private function changeConfigData($path, $value)
    {
        $this->resourceConfig->saveConfig(
            $path, 
            $value, 
            \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT, 
            \Magento\Store\Model\Store::DEFAULT_STORE_ID
        );
    }
}