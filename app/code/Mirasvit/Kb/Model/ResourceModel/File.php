<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @file  Mirasvit
 * @package   mirasvit/module-kb
 * @version   1.0.29
 * @copyright Copyright (C) 2017 Mirasvit (https://mirasvit.com/)
 */

namespace Mirasvit\Kb\Model\ResourceModel;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class File extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @var \Mirasvit\Kb\Model\fileFactory
     */
    protected $fileFactory;

    /**
     * @var \Mirasvit\Kb\Model\ResourceModel\Article\CollectionFactory
     */
    protected $articleCollectionFactory;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resource;

    /**
     * @var \Mirasvit\Core\Api\UrlRewriteHelperInterface
     */
    protected $urlRewrite;

    /**
     * @var \Magento\Framework\Model\ResourceModel\Db\Context
     */
    protected $context;

    /**
     * @var string|null
     */
    protected $resourcePrefix;

    /**
     * @param \Mirasvit\Kb\Model\fileFactory                         $fileFactory
     * @param \Mirasvit\Kb\Model\ResourceModel\Article\CollectionFactory $articleCollectionFactory
     * @param \Mirasvit\Core\Api\UrlRewriteHelperInterface               $urlRewrite
     * @param \Magento\Framework\App\CacheInterface                      $cacheManager
     * @param \Magento\Framework\Model\ResourceModel\Db\Context          $context
     * @param null                                                       $resourcePrefix
     */
    public function __construct(
        \Mirasvit\Kb\Model\File\CollectionFactory $fileFactory,
        \Mirasvit\Kb\Model\ResourceModel\Article\CollectionFactory $articleCollectionFactory,
        \Mirasvit\Core\Api\UrlRewriteHelperInterface $urlRewrite,
        \Magento\Framework\App\CacheInterface $cacheManager,
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        $resourcePrefix = null
    ) {
        $this->fileFactory = $fileFactory;
        $this->articleCollectionFactory = $articleCollectionFactory;
        $this->urlRewrite = $urlRewrite;
        $this->context = $context;
        $this->resource = $context->getResources();
        $this->resourcePrefix = $resourcePrefix;
        $this->cacheManager = $cacheManager;
        parent::__construct($context, $resourcePrefix);
    }

    /**
     *
     */
    protected function _construct()
    {
	exit("stop");
        $this->_init('mst_kb_documents', 'document_id');
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $file
     *
     * @return $this
     */
    protected function _afterLoad(\Magento\Framework\Model\AbstractModel $file)
    {
        if (!$file->getIsMassDelete()) {
            $this->loadStoreIds($file);
        }

        return parent::_afterLoad($file);
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $file
     *
     * @return $this
     */
    protected function loadStoreIds(\Magento\Framework\Model\AbstractModel $file)
    {
        $select = $this->getConnection()->select()
            ->from($this->getTable('mst_kb_file_store'))
            ->where('as_file_id = ?', $file->getId());
        if ($data = $this->getConnection()->fetchAll($select)) {
            $array = [];
            foreach ($data as $row) {
                $array[] = $row['as_store_id'];
            }
            $file->setData('store_ids', $array);
        }

        return $file;
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $file
     *
     * @return $this
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $file)
    {
        /** @var \Mirasvit\Kb\Model\file $file */
        if (!$file->getId()) {
            $file->setCreatedAt((new \DateTime())->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT));
        }
        $file->setUpdatedAt((new \DateTime())->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT));
        if (!$urlKey = $file->getUrlKey()) {
            $urlKey = $file->getName();
        }

        $file->setUrlKey($this->urlRewrite->normalize($urlKey));

        if (!$file->getChildrenCount()) {
            $file->setChildrenCount(0);
        }

        if (!$file->getId()) {
            $parentId = $file->getParentId();
            $parentfile = $this->fileFactory->create()->load($parentId);
            $file->setPath($parentfile->getPath());

            $file->setPosition($this->_getMaxPosition($file->getPath()) + 1);
            $level = count($file->getPathIds());
            $file->setLevel($level);

            if (!$file->getParentId()) { //for Root file with ID = 1
                $file->setLevel(0);
            }

            $file->setPath($file->getPath().'/');

            $toUpdateChild = explode('/', $file->getPath());

            $this->getConnection()->update(
                $this->getTable('mst_kb_documents'),
                ['children_count' => new \Zend_Db_Expr('children_count + 1')],
                ['file_id IN(?)' => $toUpdateChild]
            );
        }

        return parent::_beforeSave($file);
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $file
     *
     * @return $this
     */
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $file)
    {
        if (!$file->getIsMassStatus()) {
            $this->saveStoreIds($file);


            if ($file->getPath() ==  '/') {
                $file->setPath($file->getId());
                $this->_savePath($file);
            } elseif (substr($file->getPath(), -1) == '/') {
                $file->setPath($file->getPath().$file->getId());
                $this->_savePath($file);
            }
        }

        return parent::_afterSave($file);
    }

    /**
     * @param \Mirasvit\Kb\Model\file $file
     * @return void
     */
    protected function saveStoreIds($file)
    {
        $condition = $this->getConnection()->quoteInto('as_file_id = ?', $file->getId());
        $this->getConnection()->delete($this->getTable('mst_kb_file_store'), $condition);
        foreach ((array) $file->getData('store_ids') as $id) {
            $objArray = [
                'as_file_id' => $file->getId(),
                'as_store_id' => $id,
            ];
            $this->getConnection()->insert(
                $this->getTable('mst_kb_file_store'),
                $objArray
            );
        }
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $file
     *
     * @return $this
     */
    protected function _beforeDelete(\Magento\Framework\Model\AbstractModel $file)
    {
        /** @var \Mirasvit\Kb\Model\file $file */
        foreach ($file->getAllChildren() as $child) {
            $child->delete();
        }
        /** @var \Mirasvit\Kb\Model\Article $article */
        $articles = $this->articleCollectionFactory->create()
            ->addfileIdFilter($file->getId())
        ;
        foreach ($articles as $article) {
            $article->afterLoad();
            $article->deletefileId($file->getId());
            $article->save();
        }

        return parent::_beforeDelete($file);
    }

    /**
     * @param int $fileId
     *
     * @return int
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getChildrenCount($fileId)
    {
        $select = $this->getConnection()->select()
            ->from($this->getMainTable(), 'children_count')
            ->where('file_id = :file_id');
        $bind = ['file_id' => $fileId];

        return $this->getConnection()->fetchOne($select, $bind);
    }

     /**
     * @param \Mirasvit\Kb\Model\file $file
     *
     * @return $this
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _savePath($file)
    {
        if ($file->getId()) {
            $this->getConnection()->update(
                $this->getMainTable(),
                ['path' => $file->getPath()],
                ['file_id = ?' => $file->getId()]
            );
        }

        return $this;
    }

    /**
     * @param \Mirasvit\Kb\Model\file $file
     * @param \Mirasvit\Kb\Model\file $newParent
     * @param int                         $afterItemId
     *
     * @return int
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _processPositions($file, $newParent, $afterItemId)
    {
        $table = $this->getMainTable();
        $adapter = $this->getConnection();
        $positionField = $adapter->quoteIdentifier('position');

        $bind = [
            'position' => new \Zend_Db_Expr($positionField.' - 1'),
        ];
        $where = [
            'parent_id = ?' => $file->getParentId(),
            $positionField.' > ?' => $file->getPosition(),
        ];
        $adapter->update($table, $bind, $where);

        /*
         * Prepare position value
         */

        if ($afterItemId) {
            $select = $adapter->select()
                ->from($table, 'position')
                ->where('file_id = :file_id');
            $position = $adapter->fetchOne($select, ['file_id' => $afterItemId]);

            $bind = [
                'position' => new \Zend_Db_Expr($positionField.' + 1'),
            ];

            if (intval($newParent->getId()) == 0) {
                $where = [
                    'parent_id IS NULL',
                    $positionField.' > ?' => $position,
                ];
            } else {
                $where = [
                    'parent_id = ?' => $newParent->getId(),
                    $positionField.' > ?' => $position,
                ];
            }

            $adapter->update($table, $bind, $where);
        } elseif ($afterItemId !== null) {
            $position = 0;
            $bind = [
                'position' => new \Zend_Db_Expr($positionField.' + 1'),
            ];

            if (intval($newParent->getId()) == 0) {
                $where = [
                    'parent_id IS NULL',
                    $positionField.' > ?' => $position,
                ];
            } else {
                $where = [
                    'parent_id = ?' => $newParent->getId(),
                    $positionField.' > ?' => $position,
                ];
            }

            $adapter->update($table, $bind, $where);
        } else {
            $select = $adapter->select()
                ->from($table, ['position' => new \Zend_Db_Expr('MIN('.$positionField.')')])
                ->where('parent_id = :parent_id');
            $position = $adapter->fetchOne($select, ['parent_id' => $newParent->getId()]);
        }
        $position += 1;

        return $position;
    }

    /**
     * @param string $path
     *
     * @return int
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _getMaxPosition($path)
    {
        $adapter = $this->getConnection();
        $positionField = $adapter->quoteIdentifier('position');
        $level = count(explode('/', $path));
        if ($path == '') {
            $level = 1;
            $path = '%';
        } else {
            ++$level;
            $path .= '/%';
        }
        $bind = [
            'c_level' => $level,
            'c_path' => $path,
        ];
        $select = $adapter->select()
            ->from($this->getMainTable(), 'MAX('.$positionField.')')
            ->where($adapter->quoteIdentifier('path').' LIKE :c_path')
            ->where($adapter->quoteIdentifier('level').' = :c_level');
        $position = $adapter->fetchOne($select, $bind);

        if (!$position) {
            $position = 0;
        }

        return $position;
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $file
     *
     * @return $this
     */
    protected function _afterDelete(\Magento\Framework\Model\AbstractModel $file)
    {
        $this->urlRewrite->deleteUrlRewrite('KBASE', 'file', $file);

        return parent::_afterDelete($file);
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $file
     *
     * @return int
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getArticlesNumber(\Magento\Framework\Model\AbstractModel $file)
    {
        $resource = $this->resource;

        $readConnection = $resource->getConnection('core_read');
        $query = "
        SELECT count(distinct ac_article_id) FROM {$this->getMainTable()} c
        LEFT JOIN {$resource->getTableName('mst_kb_article_file')} ac ON c.`file_id` = ac.`ac_file_id`
        LEFT JOIN {$resource->getTableName('mst_kb_article')} art ON ac_article_id = art.article_id
        WHERE path LIKE '{$file->getPath()}%' AND art.is_active=1";

        $num = (int) $readConnection->fetchOne($query);

        return $num;
    }

    /**
     * Method used for tests. Allows to add objects with predefined ids.
     *
     * @param file $file
     *
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function saveNewObjectWrapper($file)
    {
        $this->_beforeSave($file);
        $bind = $this->_prepareDataForSave($file);
        $this->getConnection()->insert($this->getMainTable(), $bind);
        if ($this->_useIsObjectNew) {
            $file->isObjectNew(false);
        }
        $this->_afterSave($file);
    }
}
