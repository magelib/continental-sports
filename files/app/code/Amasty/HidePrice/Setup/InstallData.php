<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_HidePrice
 */


namespace Amasty\HidePrice\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallData implements InstallDataInterface
{
    /**
     * EAV setup factory
     *
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    public function __construct(\Magento\Eav\Setup\EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $this->createProductAttributes($setup);
        $this->createCategoryAttributes($setup);
        $this->createRequestTable($setup);
    }

    private function createRequestTable(ModuleDataSetupInterface $setup)
    {
        $table = $setup->getConnection()
            ->newTable($setup->getTable('amasty_hideprice_request'))
            ->addColumn(
                'request_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Request Id'
            )
            ->addColumn(
                'name',
                Table::TYPE_TEXT,
                255,
                ['default' => null, 'nullable' => false],
                'Name'
            )
            ->addColumn(
                'email',
                Table::TYPE_TEXT,
                255,
                ['default' => null, 'nullable' => false],
                'Email'
            )
            ->addColumn(
                'phone',
                Table::TYPE_TEXT,
                255,
                ['default' => null, 'nullable' => false],
                'Phone'
            )
            ->addColumn(
                'product_id',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'default' => '0'],
                'Product id'
            )
            ->addColumn(
                'store_id',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => 0],
                'Store id'
            )
            ->addColumn(
                'comment',
                Table::TYPE_TEXT,
                null,
                ['default' => null, 'nullable' => false],
                'Comment'
            )
            ->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'Creation Time'
            )
            ->addColumn(
                'status',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => 0],
                'Status'
            )

            ->addColumn(
                'message_text',
                Table::TYPE_TEXT,
                null,
                ['nullable' => false, 'default' => null],
                'Message Text'
            )
            ->setComment('Amasty Hide price Requests');
        $setup->getConnection()->createTable($table);
    }

    private function createProductAttributes(ModuleDataSetupInterface $setup)
    {
        /** @var \Magento\Eav\Setup\EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'am_hide_price_mode',
            [
                'type'              => 'int',
                'backend'           => '',
                'frontend'          => '',
                'label'             => __('Display Price Mode'),
                'input'             => 'select',
                'class'             => '',
                'source'            => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                'global'            => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'visible'           => false,
                'required'          => false,
                'user_defined'      => false,
                'default'           => '',
                'searchable'        => false,
                'filterable'        => false,
                'comparable'        => false,
                'visible_on_front'  => false,
                'unique'            => false,
                'apply_to'          => '',
                'is_configurable'   => false
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'am_hide_price_customer_gr',
            [
                'type'              => 'varchar',
                'backend'           => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                'frontend'          => '',
                'label'             => __('Use Current Price Mode By Customer Group'),
                'input'             => 'multiselect',
                'class'             => '',
                'source'            => 'Magento\Customer\Model\Customer\Source\Group',
                'global'            => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'visible'           => false,
                'required'          => false,
                'user_defined'      => false,
                'default'           => '',
                'searchable'        => false,
                'filterable'        => false,
                'comparable'        => false,
                'visible_on_front'  => false,
                'unique'            => false,
                'apply_to'          => '',
                'is_configurable'   => false
            ]
        );
    }

    private function createCategoryAttributes(ModuleDataSetupInterface $setup)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'am_hide_price_mode_cat',
            [
                'type'              => 'int',
                'label'             => __('Display Price Mode'),
                'input'             => 'select',
                'required'          => false,
                'sort_order'        => 4,
                'global'            => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'wysiwyg_enabled' => false,
                'is_html_allowed_on_front' => false,
                'group' => __('General Information'),
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'am_hide_price_customer_gr_cat',
            [
                'type'              => 'varchar',
                'backend'           => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                'label'             => __('Use Current Price Mode By Customer Group'),
                'input'             => 'multiselect',
                'required'          => false,
                'sort_order'        => 4,
                'global'            => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'wysiwyg_enabled' => false,
                'is_html_allowed_on_front' => false,
                'group' => __('General Information'),
            ]
        );
    }
}
