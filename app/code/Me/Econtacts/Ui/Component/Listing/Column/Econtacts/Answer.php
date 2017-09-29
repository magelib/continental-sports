<?php
/**
 * Copyright © 2016 Magevolve Ltd. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Me\Econtacts\Ui\Component\Listing\Column\Econtacts;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\Filter\FilterManager;

class Answer extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * Column name
     */
    const NAME = 'column.answer';

    /**
     * Filter manager
     *
     * @var FilterManager
     */
    protected $_filter;

    /**
     * Answer constructor.
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param FilterManager $filter
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        FilterManager $filter,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->_filter = $filter;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item[$fieldName])) {
                    $item[$fieldName] = strip_tags($item[$fieldName]);
                    $item[$fieldName] = $this->_filter->truncate(
                        $item[$fieldName],
                        [
                            'length' => 256,
                            'etc' => '...',
                            'remainder' => '',
                            'breakWords' => true
                        ]
                    );
                }
            }
        }

        return $dataSource;
    }
}
