<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-search
 * @version   1.0.42
 * @copyright Copyright (C) 2017 Mirasvit (https://mirasvit.com/)
 */



namespace Mirasvit\Search\Index\Magento\Catalog\Product;

use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Mirasvit\Search\Api\Data\Index\DataMapperInterface;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\ObjectManagerInterface;
use Magento\Catalog\Model\Category;
use Mirasvit\Search\Api\Repository\IndexRepositoryInterface;

class DataMapper implements DataMapperInterface
{
    /**
     * @var EavConfig
     */
    private $eavConfig;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var ResourceConnection
     */
    private $resource;

    /**
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    private $connection;

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var IndexRepositoryInterface
     */
    private $indexRepository;

    public function __construct(
        IndexRepositoryInterface $indexRepository,
        EavConfig $eavConfig,
        ProductRepositoryInterface $productRepository,
        ResourceConnection $resource,
        ObjectManagerInterface $objectManager
    ) {
        $this->indexRepository = $indexRepository;
        $this->eavConfig = $eavConfig;
        $this->productRepository = $productRepository;
        $this->resource = $resource;
        $this->connection = $this->resource->getConnection();
        $this->objectManager = $objectManager;
    }

    /**
     * {@inheritdoc}
     */
    public function map(array $documents, $dimensions, $indexIdentifier)
    {
        $this->addCategoryData($documents);
        $this->addCustomOptions($documents);
        $this->addBundledOptions($documents);
        $this->addProductIdData($documents);

        return $documents;
    }

    /**
     * @param array &$index
     * @return $this
     */
    protected function addCustomOptions(&$index)
    {
        if (!$this->getIndex()->getProperty('include_custom_options')) {
            return $this;
        }

        $productIds = array_keys($index);
        $this->connection->query('SET SESSION group_concat_max_len = 1000000;');

        $select = $this->connection->select()
            ->from(['main_table' => $this->resource->getTableName('catalog_product_option')], ['product_id'])
            ->joinLeft(
                ['otv' => $this->resource->getTableName('catalog_product_option_type_value')],
                'main_table.option_id = otv.option_id',
                ['sku' => new \Zend_Db_Expr("GROUP_CONCAT(otv.`sku` SEPARATOR ' ')")]
            )
            ->joinLeft(
                ['ott' => $this->resource->getTableName('catalog_product_option_type_title')],
                'otv.option_type_id = ott.option_type_id',
                ['title' => new \Zend_Db_Expr("GROUP_CONCAT(ott.`title` SEPARATOR ' ')")]
            )
            ->where('main_table.product_id IN (?)', $productIds)
            ->group('product_id');

        foreach ($this->connection->fetchAll($select) as $row) {
            if (!isset($index[$row['product_id']]['options'])) {
                $index[$row['product_id']]['options'] = '';
            }
            $index[$row['product_id']]['options'] .= ' ' . $row['title'];
            $index[$row['product_id']]['options'] .= ' ' . $row['sku'];
        }

        return $this;
    }

    /**
     * @param array &$index
     * @return $this
     */
    protected function addBundledOptions(&$index)
    {
        if (!$this->getIndex()->getProperty('include_bundled')) {
            return $this;
        }

        $productIds = array_keys($index);
        $this->connection->query('SET SESSION group_concat_max_len = 1000000;');

        $select = $this->connection->select()
            ->from(
                ['main_table' => $this->resource->getTableName('catalog_product_entity')],
                ['sku' => new \Zend_Db_Expr("GROUP_CONCAT(main_table.`sku` SEPARATOR ' ')")]
            )
            ->group('cpr.parent_id');

        // enterprise
        $tbl = $this->connection->describeTable($this->resource->getTableName('catalog_product_entity'));
        if (isset($tbl['row_id'])) {
            $select
                ->joinLeft(
                    ['cpr' => $this->resource->getTableName('catalog_product_relation')],
                    'main_table.entity_id = cpr.child_id',
                    []
                )->joinLeft(
                    ['cpe' => $this->resource->getTableName('catalog_product_entity')],
                    'cpe.row_id = cpr.parent_id',
                    ['parent_id' => 'entity_id']
                )->where('cpe.entity_id IN (?)', $productIds);
        } else {
            $select
                ->joinLeft(
                    ['cpr' => $this->resource->getTableName('catalog_product_relation')],
                    'main_table.entity_id = cpr.child_id',
                    ['parent_id']
                )
                ->where('cpr.parent_id IN (?)', $productIds);
        }

        foreach ($this->connection->fetchAll($select) as $row) {
            if (!isset($index[$row['parent_id']]['options'])) {
                $index[$row['parent_id']]['options'] = '';
            }
            $index[$row['parent_id']]['options'] .= ' ' . $row['sku'];
        }

        return $this;
    }

    /**
     * @param array &$index
     * @return $this
     */
    protected function addProductIdData(&$index)
    {
        if (!$this->getIndex()->getProperty('include_id')) {
            return $this;
        }

        foreach ($index as $entityId => &$data) {
            if (!isset($data['options'])) {
                $data['options'] = '';
            }

            $data['options'] .= ' ' . $entityId;
        }

        return $this;
    }

    /**
     * @param array &$index
     * @return $this
     */
    protected function addCategoryData(&$index)
    {
        if (!$this->getIndex()->getProperty('include_category')) {
            return $this;
        }

        $entityTypeId = $this->objectManager->create('Magento\Eav\Model\Entity')
            ->setType(Category::ENTITY)->getTypeId();

        /** @var \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attribute */
        $attribute = $this->objectManager->create('Magento\Catalog\Model\ResourceModel\Eav\Attribute')
            ->loadByCode($entityTypeId, 'name');

        $tbl = $this->connection->describeTable($attribute->getBackend()->getTable());

        $pk = 'entity_id';

        if (isset($tbl['row_id'])) {
            $pk = 'row_id';
        }

        $productIds = array_keys($index);

        $valueSelect = $this->connection->select()
            ->from(
                ['cc' => $this->resource->getTableName('catalog_category_entity')],
                [new \Zend_Db_Expr("GROUP_CONCAT(vc.value SEPARATOR ' ')")]
            )
            ->joinLeft(
                ['vc' => $attribute->getBackend()->getTable()],
                'cc.entity_id = vc.' . $pk,
                []
            )
            ->where("LOCATE(CONCAT('/', CONCAT(cc.entity_id, '/')), CONCAT(ce.path, '/'))")
            ->where('vc.attribute_id = ?', $attribute->getId());

        $columns = [
            'product_id' => 'product_id',
            'category'   => new \Zend_Db_Expr('(' . $valueSelect . ')'),
        ];

        $select = $this->connection->select()
            ->from([$this->resource->getTableName('catalog_category_product')], $columns)
            ->joinLeft(
                ['ce' => $this->resource->getTableName('catalog_category_entity')],
                'category_id = ce.entity_id',
                []
            )
            ->where('product_id IN (?)', $productIds);

        foreach ($this->connection->fetchAll($select) as $row) {
            if (!isset($index[$row['product_id']]['options'])) {
                $index[$row['product_id']]['options'] = '';
            }

            if (is_array($index[$row['product_id']]['options'])) {
                $index[$row['product_id']]['options'] = implode(' ', $index[$row['product_id']]['options']);
            }
            $index[$row['product_id']]['options'] .= ' ' . $row['category'];
        }

        return $this;
    }

    /**
     * @return \Mirasvit\Search\Api\Data\IndexInterface
     */
    public function getIndex()
    {
        return $this->indexRepository->get('catalogsearch_fulltext');
    }
}