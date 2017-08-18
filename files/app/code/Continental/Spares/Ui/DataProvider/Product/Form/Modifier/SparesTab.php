<?php
namespace Continental\Spares\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Framework\UrlInterface;
use Magento\Ui\Component\Container;
use Magento\Ui\Component\Form\Fieldset;

class CustomTab extends AbstractModifier
{

    const CUSTOM_TAB_INDEX = 'custom_tab';
    const CUSTOM_TAB_CONTENT = 'content';

    /**
     * @var \Magento\Catalog\Model\Locator\LocatorInterface
     */
    protected $locator;

    /**
     * @var ArrayManager
     */
    protected $arrayManager;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var array
     */
    protected $meta = [];

    /**
     * @param LocatorInterface $locator
     * @param ArrayManager $arrayManager
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        LocatorInterface $locator,
        ArrayManager $arrayManager,
        UrlInterface $urlBuilder
    ) {
        $this->locator = $locator;
        $this->arrayManager = $arrayManager;
        $this->urlBuilder = $urlBuilder;
    }

    public function modifyData(array $data)
    {
        return $data;
    }

    public function modifyMeta(array $meta)
    {
        $this->meta = $meta;
        $this->addCustomTab();

        return $this->meta;
    }

    protected function addCustomTab()
    {
        $this->meta = array_merge_recursive(
            $this->meta,
            [
                static::CUSTOM_TAB_INDEX => $this->getTabConfig(),
            ]
        );
    }

    protected function getTabConfig()
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Spares Tab'),
                        'componentType' => Fieldset::NAME,
                        'dataScope' => '',
                        'provider' => static::FORM_NAME . '.product_form_data_source',
                        'ns' => static::FORM_NAME,
                        'collapsible' => true,
                    ],
                ],
            ],
            'children' => [
                static::CUSTOM_TAB_CONTENT => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'label' => null,
                                'formElement' => Container::NAME,
                                'componentType' => Container::NAME,
                                'template' => 'ui/form/components/complex',
                                'content' => __('You can write text here, ') .
                                    ' or html: ' . 
                                    '<h3>Text</h3>' . 
                                    ', or blocks html (using toHtml method).',
                                'sortOrder' => 10,
                            ],
                        ],
                    ],
                    'children' => [],
                ],
            ],
        ];
    }
}