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
 * @package   mirasvit/module-search-report
 * @version   1.0.1
 * @copyright Copyright (C) 2017 Mirasvit (https://mirasvit.com/)
 */



namespace Mirasvit\SearchReport\Reports;

use Mirasvit\SearchReport\Api\Data\LogInterface;
use Mirasvit\Report\Api\Data\Query\ColumnInterface;
use Mirasvit\Report\Model\AbstractReport;

class Volume extends AbstractReport
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return __('Search Volume');
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifier()
    {
        return 'search_report_volume';
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->setBaseTable(LogInterface::TABLE_NAME);
        $this->addFastFilters([
            'mst_search_report_log|period_filter',
        ]);

        $this->setDefaultColumns([
            'mst_search_report_log|searches',
            'mst_search_report_log|unique_searches',
            'mst_search_report_log|users',
            'mst_search_report_log|engagement'
        ]);


        $this->addAvailableColumns(
            $this->context->getMapRepository()
                ->getTable('mst_search_report_log')->getColumns(ColumnInterface::TYPE_AGGREGATION)
        );

        $this->setDefaultDimension('mst_search_report_log|day');

        $this->addAvailableDimensions([
            'mst_search_report_log|hour_of_day',
            'mst_search_report_log|day',
            'mst_search_report_log|week',
            'mst_search_report_log|month',
            'mst_search_report_log|year',
        ]);

        $this->setGridConfig([
            'paging' => true,
        ]);
        $this->setChartConfig([
            'chartType' => 'column',
            'vAxis'     => 'mst_search_report_log|searches',
        ]);
    }
}