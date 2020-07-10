<?php
 
namespace Ppsoft\InstallConfig\Setup;
 
use Magento\Cms\Model\BlockFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
 
 
class InstallData implements InstallDataInterface
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
     * Construct
     *
     * @param \Magento\Cms\Model\BlockFactory $blockFactory
     */
    public function __construct(
        \Magento\Cms\Model\BlockFactory $blockFactory,
        \Magento\Framework\App\Config\ConfigResource\ConfigInterface  $resourceConfig
    )
    {
        $this->_blockFactory = $blockFactory;
        $this->resourceConfig = $resourceConfig;
    }
 
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $this->resourceConfig->saveConfig(
                'carriers/pickupatstore/settings/date_settings/date', 
                0, 
                \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT, 
                \Magento\Store\Model\Store::DEFAULT_STORE_ID
            );
            
        $this->resourceConfig->saveConfig(
                'carriers/pickupatstore/settings/time_settings/time', 
                0, 
                \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT, 
                \Magento\Store\Model\Store::DEFAULT_STORE_ID
            );
    }
} 