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


namespace Mirasvit\SearchAutocomplete\Index\Magento\Cms;

use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Cms\Helper\Page as PageHelper;
use Mirasvit\SearchAutocomplete\Index\AbstractIndex;

class Page extends AbstractIndex
{
    /**
     * @var PageHelper
     */
    private $pageHelper;

    /**
     * @var PageRepositoryInterface
     */
    private $pageRepository;

    public function __construct(
        PageRepositoryInterface $pageRepository,
        PageHelper $pageHelper
    ) {
        $this->pageHelper = $pageHelper;
        $this->pageRepository = $pageRepository;
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
                    'title' => $item->getData('title'),
                    'url'   => $item->getData('url'),
                ];

                if (count($items) >= $this->index->getLimit()) {
                    break;
                }
            }
        } else {
            /** @var \Magento\Cms\Model\Page $page */
            foreach ($this->getCollection() as $page) {
                $items[] = [
                    'title' => $page->getTitle(),
                    'url'   => $this->pageHelper->getPageUrl($page->getIdentifier()),
                ];
            }
        }

        return $items;
    }

    /**
     * @param array  $documents
     * @param array  $dimensions
     * @param string $indexIdentifier
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function map(array $documents, $dimensions, $indexIdentifier)
    {
        foreach ($documents as $pageId => $document) {
            $page = $this->pageRepository->getById($pageId);

            $document['autocomplete'] = [
                'title' => $page->getTitle(),
                'url'   => $this->pageHelper->getPageUrl($page->getIdentifier()),
            ];

            $documents[$pageId] = $document;
        }

        return $documents;
    }
}
