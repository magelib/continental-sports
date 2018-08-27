<?php

namespace Continental\GalleryGrid\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Catalog\Model\ResourceModel\Product\Gallery;

class InstallSchema implements InstallSchemaInterface {

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context) {
        $setup->startSetup();

        $setup->getConnection()->addColumn(
            $setup->getTable(Gallery::GALLERY_TABLE), 'gridable', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                'unsigned' => true,
                'nullable' => false,
                'default' => 0,
                'comment' => 'use for GalleryGrid'                ]
        );

        $setup->endSetup();
    }

}