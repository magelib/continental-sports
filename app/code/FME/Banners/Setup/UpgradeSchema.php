<?php
namespace FME\Banners\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{

    /**
     * {@inheritdoc}
     */
    public function upgrade(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    )
    {
        $installer = $setup;

        $installer->startSetup();

        $tableName = $installer->getTable('fme_banners');
/*
        if (version_compare($context->getVersion(), '1.0.2', '<')) {
            $installer->getConnection()
                ->addColumn(
                $installer->getTable('fme_banners'),
                'testlinktext',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'comment' => 'Text for Learn More buttons',
                    'default' => null
                ]
            );
        }
  */
        if (version_compare($context->getVersion(), '1.0.3', '<')) {


            $setup->getConnection()->addColumn($tableName, 'linktext', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length'    => 255,
                'unsigned' => true,
                'nullable' => false,
                'default' => '',
                'comment' => 'Text for Learn More Buttons'
            ]);
        }
        $installer->endSetup();
    }

}