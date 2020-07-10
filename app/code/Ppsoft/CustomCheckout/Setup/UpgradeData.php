<?php
/**
 * Created by PhpStorm.
 * User: oscar
 * Date: 23-09-19
 * Time: 11:05 AM
 */

namespace Ppsoft\CustomCheckout\Setup;


use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;

class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var \Magento\Framework\App\Config\ConfigResource\ConfigInterface
     */
    private $resourceConfig;

    public function __construct(\Magento\Framework\App\Config\ConfigResource\ConfigInterface  $resourceConfig)
    {
        $this->resourceConfig = $resourceConfig;
    }

    /**
     * Upgrades data for a module
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if(version_compare($context->getVersion(), '1.0.4', '<')){
            $this->resourceConfig->saveConfig(
                'payment/payme/title',
                'Tarjeta de Crédito/Débito',
                \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
                \Magento\Store\Model\Store::DEFAULT_STORE_ID);
        }
        $setup->endSetup();
    }
}