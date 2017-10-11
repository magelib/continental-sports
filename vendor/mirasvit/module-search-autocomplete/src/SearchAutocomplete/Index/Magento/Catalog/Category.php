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
 * @package   mirasvit/module-search-autocomplete
 * @version   1.1.17
 * @copyright Copyright (C) 2017 Mirasvit (https://mirasvit.com/)
 */


namespace Mirasvit\SearchAutocomplete\Index\Magento\Catalog;

use Magento\Catalog\Api\Data\CategoryInterface;
use Mirasvit\SearchAutocomplete\Index\AbstractIndex;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Api\CategoryRepositoryInterface;

class Category extends AbstractIndex
{
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var CategoryRepositoryInterface
     */
    protected $categoryRepository;

    public function __construct(
        StoreManagerInterface $storeManager,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->storeManager = $storeManager;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getItems()
    {
        $items = [];

        $responseItems = $this->getQueryResponseItems();
        if ($responseItems) {
            foreach ($responseItems as $item) {
                $items[] = [
                    'name' => $item->getData('path'),
                    'url'  => $item->getData('url'),
                ];

                if (count($items) >= $this->index->getLimit()) {
                    break;
                }
            }
        } else {
            /** @var \Magento\Catalog\Model\Category $category */
            foreach ($this->getCollection() as $category) {
                $items[] = [
                    'name' => $this->getFullPath($category),
                    'url'  => $category->getUrl(),
                ];
            }
        }

        return $items;
    }

    /**
     * List of parent categories
     *
     * @param CategoryInterface $category
     * @return string
     */
    public function getFullPath(CategoryInterface $category)
    {
        $store = $this->storeManager->getStore();
        $rootId = $store->getRootCategoryId();

        $result = [
            $category->getName(),
        ];

        do {
            if (!$category->getParentId()) {
                break;
            }
            $category = $this->categoryRepository->get($category->getParentId());

            if (!$category->getIsActive() && $category->getId() != $rootId) {
                break;
            }

            if ($category->getId() != $rootId) {
                $result[] = $category->getName();
            }
        } while ($category->getId() != $rootId);

        $result = array_reverse($result);

        return implode(' > ', $result);
    }

    /**
     * {@inheritdoc}
     */
    public function map(array $documents, $dimensions, $indexIdentifier)
    {
        foreach ($documents as $id => $document) {
            $category = $this->categoryRepository->get($id);

            $document['autocomplete'] = [
                'name' => $category->getName(),
                'path' => $this->getFullPath($category),
                'url'  => $category->getUrl(),
            ];

            $documents[$id] = $document;
        }

        return $documents;
    }
}
