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



namespace Continental\General\Model\Searchautocomplete\Index\Magento\Catalog;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Block\Product\ReviewRendererInterface;
use Magento\Catalog\Helper\Image as ImageHelper;
use Magento\Review\Block\Product\ReviewRenderer;
use Magento\Review\Model\ReviewFactory;
use Mirasvit\SearchAutocomplete\Model\Config;
use Mirasvit\SearchAutocomplete\Index\AbstractIndex;
use Magento\Catalog\Helper\Data as CatalogHelper;
use Magento\Framework\Pricing\Helper\Data as PricingHelper;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Product extends \Mirasvit\SearchAutocomplete\Index\Magento\Catalog\Product
{

    /**
     * @var Config
     */
    private $config;

    /**
     * @var ReviewFactory
     */
    private $reviewFactory;

    /**
     * @var ReviewRenderer
     */
    private $reviewRenderer;

    /**
     * @var ImageHelper
     */
    private $imageHelper;

    /**
     * @var CatalogHelper
     */
    private $catalogHelper;

    /**
     * @var PricingHelper
     */
    private $pricingHelper;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    public function __construct(
        Config $config,
        ReviewFactory $reviewFactory,
        ReviewRenderer $reviewRenderer,
        ImageHelper $imageHelper,
        CatalogHelper $catalogHelper,
        PricingHelper $pricingHelper,
        ProductRepositoryInterface $productRepository,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory
    ) {
        $this->config = $config;
        $this->reviewFactory = $reviewFactory;
        $this->reviewRenderer = $reviewRenderer;
        $this->_categoryFactory = $categoryFactory;
        $this->imageHelper = $imageHelper;
        $this->catalogHelper = $catalogHelper;
        $this->pricingHelper = $pricingHelper;
        $this->productRepository = $productRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getItems()
    {
        \Magento\Framework\Profiler::start(__METHOD__);
        $items = [];

        $responseItems = $this->getQueryResponseItems();
        if ($responseItems) {
            foreach ($responseItems as $item) {
                $items[] = [
                    'sku'         => $item->getData('sku'),
                    'name'        => $item->getData('name'),
                    'url'         => $item->getData('url'),
                    'description' => $item->getData('description'),
                    'image'       => $item->getData('image'),
                    'price'       => 1,
                    'rating'      => $item->getData('rating'),
                ];

                if (count($items) >= $this->index->getLimit()) {
                    break;
                }
            }
        } else {
            $collection = $this->getCollection();

            $collection->addAttributeToSelect('name')
                ->addAttributeToSelect('short_description')
                ->addAttributeToSelect('description');

            if ($this->config->isShowRating()) {
                $this->reviewFactory->create()->appendSummary($collection);
            }

            /** @var \Magento\Catalog\Model\Product $product */
            foreach ($collection as $product) {
                $items[] = $this->mapProduct($product);
            }
        }

        \Magento\Framework\Profiler::stop(__METHOD__);

        return $items;
    }

    /**
     * @param array $documents
     * @param array $dimensions
     * @param string $indexIdentifier
     * @return array
     * @SuppressWarnings(PHPMD)
     */
    public function map(array $documents, $dimensions, $indexIdentifier)
    {
        if (!$this->config->isFastMode()) {
            return $documents;
        }

        foreach ($documents as $productId => $document) {
            /** @var \Magento\Catalog\Model\Product $product */
            $product = $this->productRepository->getById($productId);

            $document['autocomplete_raw'] = $this->mapProduct($product);;

            $documents[$productId] = $document;
        }

        return $documents;
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return array
     * @SuppressWarnings(PHPMD)
     */
    private function mapProduct($product)
    {
        $item = [
            'name'        => $product->getName(),
            'url'         => $product->getProductUrl(),
            'sku'         => null,
            'description' => null,
            'image'       => null,
            'price'       => null,
            'rating'      => null,
        ];

        if ($this->config->isShowShortDescription()) {
            $item['description'] = html_entity_decode(
                strip_tags($product->getDataUsingMethod('description'))
            );
        }

        if ($this->config->isShowSku()) {
            $item['sku'] = html_entity_decode(
                strip_tags($product->getDataUsingMethod('sku'))
            );
        }

        $image = false;
        if ($product->getImage() && $product->getImage() != 'no_selection') {
            $image = $product->getImage();
        } elseif ($product->getSmallImage() && $product->getSmallImage() != 'no_selection') {
            $image = $product->getSmallImage();
        }

        if ($this->config->isShowImage() && $image) {
            $item['image'] = $this->imageHelper->init($product, 'product_page_image_small')
                ->setImageFile($image)
                ->resize(65 * 2, 80 * 2)
                ->getUrl();
        }

        if ($this->config->isShowPrice()) {
            $product->setData('final_price', null); #reset wrong calculated price

            $item['price'] = $this->catalogHelper->getTaxPrice($product, $product->getFinalPrice());
            if ($product->getFinalPrice() > $item['price']) {
                $item['price'] = $product->getFinalPrice();
            }
            $item['price'] = $this->pricingHelper->currency($item['price'], false, false);

            // IF POA product hide the price
            // a product is a POA product if its assigned to the POA category
            $collection = $this->_categoryFactory->create()->getCollection()->addAttributeToFilter('name', 'POA')->setPageSize(1);
            $categoryId = $collection->getFirstItem()->getId();
            if ($collection->getSize()) {
                $key = array_search($categoryId, $product->getCategoryIds());
                if($key !== false) {
                    $item['price'] = '';
                }
            }
        }

        if ($this->config->isShowRating()) {
            $item['rating'] = $this->reviewRenderer
                ->getReviewsSummaryHtml($product, ReviewRendererInterface::SHORT_VIEW);
        }

        return $item;
    }
}
