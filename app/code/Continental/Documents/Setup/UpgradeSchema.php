<?php

namespace Continental\Documents\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{


    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        /**
         * Create table 'documents'
         */

        $setup->getConnection()->addColumn($installer->getTable('continental_documents'), 'description',
            array(
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'nullable' => true,
                'length' => '64K',
                'default' => null,
                'comment' => 'Description'
            )
        );

        $setup->getConnection()->addColumn($installer->getTable('continental_documents'), 'assigns',
            array(
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'nullable' => true,
                'length' => '64K',
                'default' => null,
                'comment' => 'Assigns'
            )
        );

        $installer->endSetup();
    }
}
