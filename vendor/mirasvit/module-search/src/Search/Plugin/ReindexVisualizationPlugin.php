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



namespace Mirasvit\Search\Plugin;

use Magento\CatalogSearch\Model\Indexer\Fulltext;
use Magento\Framework\Registry;
use Mirasvit\Search\Api\Data\IndexInterface;
use Mirasvit\Search\Api\Repository\IndexRepositoryInterface;
use Mirasvit\Search\Api\Service\IndexServiceInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ReindexVisualizationPlugin
{
    /**
     * @var OutputInterface
     */
    private $output;

    public function __construct(
        Registry $registry
    ) {
        $this->output = $registry->registry('output');
    }

    //    /**
    //     * {@inheritdoc}
    //     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
    //     */
    //    public function beforeRebuildStoreIndex($action, $storeId, $productIds = null)
    //    {
    //        if ($this->output) {
    //            $this->output->write(PHP_EOL . "Reindex store " . $storeId . PHP_EOL);
    //        }
    //    }
    //
    //    /**
    //     * {@inheritdoc}
    //     */
    //    public function afterNext()
    //    {
    //        if ($this->output) {
    //            $this->output->write('.');
    //        }
    //    }
}
