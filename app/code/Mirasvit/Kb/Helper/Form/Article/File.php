<?php

namespace Mirasvit\Kb\Helper\Form\Article;

use Mirasvit\Kb\Model\ResourceModel\File\CollectionFactory as FileCollectionFactory;
use Mirasvit\Kb\Model\FileFactory;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Framework\DB\Helper as DbHelper;

class File extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $filesTrees;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Store\Model\StoreFactory $storeFactory,
        LocatorInterface $locator,
        DbHelper $dbHelper,
        \Magento\Framework\App\Helper\Context $context
    )
    {
        $this->storeManager = $storeManager;
        $this->storeFactory = $storeFactory;
        $this->locator = $locator;
        $this->dbHelper = $dbHelper;
        $this->context = $context;


        parent::__construct($context);
    }

    public function getFilesField($article, $container, $updateUrl, $renderUrl)
    {
        return '

    <div data-role="spinner" data-component="' . $container . '.' . $container . '"
         class="admin__form-loading-mask">
        <div class="spinner">
            <span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span>
        </div>
    </div>
    <div data-bind="scope: \'' . $container . '.' . $container . '\'" class="entry-edit form-inline">
        <!-- ko template: getTemplate() --><!-- /ko -->
    </div>
        <script type="text/x-magento-init">
    {
        "*": ' . json_encode($this->getFilesJsLayout($article, $container, $updateUrl, $renderUrl)) . '
    }
</script>
    ';
    }

    public function getFileField($article)
    {
        return [
            "type"      => "form.select",
            "name"      => "file_ids",
            "dataScope" => "file_ids",
            "config"    => [
                "code"             => "file_ids",
                "component"        => "Mirasvit_Kb/js/form/components/article-edit-group",
                "template"         => "ui/form/field",
                "dataType"         => "text",
                "formElement"      => "select",
                "componentType"    => "field",
                "label"            => "files",
                "source"           => 'file-details',
                "scopeLabel"       => '[GLOBAL]',
                "sortOrder"        => 90,
                "globalScope"      => true,
                "filterOptions"    => true,
                "chipsEnabled"     => true,
                "disableLabel"     => true,
                "levelsVisibility" => "1",
                "elementTmpl"      => "ui/grid/filters/elements/ui-select",
                "options"          => $this->getFilesTree(),
                "value"            => array_map('intval', $article->getFilesIds()), // var type is important here
                'visible'          => 1,
                "listens"          => [
                    "index=create_file:responseData" => "setParsed",
                    "newOption"                          => "toggleOptionSelected"
                ],
                "config"           => [
                    "dataScope" => "file_ids",
                    "sortOrder" => 10
                ]
            ]
        ];
    }

    public function getFilesJsLayout($article, $container, $updateUrl, $renderUrl)
    {
        return [
            "Magento_Ui/js/core/app" => [
                "types" => [
                    "dataSource" => [
                        "component" => "Magento_Ui/js/form/provider"
                    ],
                    "container" => [
                        "extends" => $container
                    ],
                    "select" => [
                        "extends" => $container
                    ],
                    "multiselect" => [
                        "extends" => $container
                    ],
                    "form.select" => [
                        "extends" => "select"
                    ],
                    "fieldset" => [
                        "component" => "Magento_Ui/js/form/components/fieldset",
                        "extends"   => $container,
                    ],
                    "html_content" => [
                        "component" => "Magento_Ui/js/form/components/html",
                        "extends"   => $container,
                    ],
                    "form.multiselect" => [
                        "extends"   => 'multiselect',
                    ],
                    $container => [
                        "component" => "Magento_Ui/js/form/form",
                        "provider"  => $container.".files_data_source",
                        "deps"      => $container.".files_data_source",
                        "namespace" => $container
                    ]
                ],
                "components" => [
                    $container => [
                        "children" => [
                            $container => [
                                "type"     => $container,
                                "name"     => $container,
                                "children" => [
                                    "create_file_modal" => $this->getFilesModal($updateUrl, $renderUrl),
                                    'file-details' => [
                                        "children" => [
                                            "container_file_ids" => [
                                                "type"     => "container",
                                                "name"     => "container_file_ids",
                                                "children" => [
                                                    "file_ids"           => $this->getFileField($article),
                                                    "create_file_button" => $this->getFilesCreateButton()
                                                ],
                                                "dataScope" => "",
                                                "config"    => [
                                                    "component"     => "Magento_Ui/js/form/components/group",
                                                    "label"         => __("files"),
                                                    "breakLine"     => false,
                                                    "formElement"   => "container",
                                                    "componentType" => "container",
                                                    "scopeLabel"    => __("[GLOBAL]"),
                                                    "sortOrder"     => 0
                                                ]
                                            ]
                                        ],
                                        'config' => [
                                            'collapsible'   => false,
                                            'componentType' => 'fieldset',
                                            'label'         => '',
                                            'sortOrder'     => 0,
                                        ],
                                        'name'      => 'file-details',
                                        'dataScope' => 'data.file',
                                        'type'      => 'fieldset',
                                    ],
                                ]
                            ],
                            "files_data_source" => [
                                "type"      => "dataSource",
                                "name"      => "files_data_source",
                                "dataScope" => $container,
                                "config"    => [
                                    "data" => [
                                        "article" => $article->getData()
                                    ],
                                    "params" => [
                                        "namespace" => $container
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }

    public function getFilesCreateButton()
    {
        return [
            "type"   => "container",
            "name"   => "create_files_button",
            "config" => [
                "component"         => "Magento_Ui/js/form/components/button",
                "title"             => __("New Document"),
                "formElement"       => "container",
                "additionalClasses" => "admin__field-small",
                "componentType"     => "container",
                "template"          => "ui/form/components/button/container",
                "actions"           => [
                    [
                        "targetName" => "kb_article_files.kb_article_files.create_file_modal",
                        "actionName" => "toggleModal"
                    ],
                    [
                        "targetName" => "kb_article_files.kb_article_files.create_file_modal.".
                            "create_file",
                        "actionName" => "render"
                    ],
                    [
                        "targetName" => "kb_article_files.kb_article_files.create_file_modal.".
                            "create_file",
                        "actionName" => "resetForm"
                    ]
                ],
                "additionalForGroup" => true,
                "provider"           => false,
                "source"             => "file-details",
                "displayArea"        => "insideGroup",
                "sortOrder"          => 20
            ]
        ];
    }

    public function getFilesModal($updateUrl, $renderUrl)
    {
        return [
            "type"     => "container",
            "name"     => "create_file_modal",
            "children" => [
                "create_file" => [
                    "type"      => "container",
                    "name"      => "create_file",
                    "dataScope" => "",
                    "config"    => [
                        "component"        => "Magento_Ui/js/form/components/insert-form",
                        "label"            => "",
                        "componentType"    => "container",
                        "update_url"       => $updateUrl,
                        "render_url"       => $renderUrl,
                        "autoRender"       => false,
                        "ns"               => "kb_new_file_form",
                        "externalProvider" => "kb_new_file_form.new_file_form_data_source",
                        "toolbarContainer" => '${ $.parentName }',
                        "formSubmitType"   => "ajax"
                    ]
                ]
            ],
            "config" => [
                "component"     => "Magento_Ui/js/modal/modal-component",
                "options"       => [
                    "type"  => "slide",
                    "title" => __("New File")
                ],
                "isTemplate"    => false,
                "componentType" => "modal",
                "imports"       => [
                    "state" => "!index=create_file:responseStatus"
                ]
            ]
        ];
    }

    public function getFilesTree($filter = null)
    {
        if (isset($this->filesTrees[$filter])) {
            return $this->filesTrees[$filter];
        }

        /* @var $matchingNamesCollection \Mirasvit\Kb\Model\ResourceModel\File\Collection */
        $matchingNamesCollection = $this->fileCollectionFactory->create()
            ->addAttributeToSelect('path')
            ->addAttributeToFilter('file_id', ['neq' => FileModel::TREE_ROOT_ID])
        ;

        if ($filter !== null) {
            $matchingNamesCollection->addAttributeToFilter(
                'name',
                ['like' => $this->dbHelper->addLikeEscape($filter, ['position' => 'any'])]
            );
        }

        $shownfilesIds = [];

        /** @var \Magento\Catalog\Model\File $file */
        foreach ($matchingNamesCollection as $file) {
            foreach (explode('/', $file->getPath()) as $parentId) {
                $shownfilesIds[$parentId] = 1;
            }
        }

        /* @var $collection \Mirasvit\Kb\Model\ResourceModel\File\Collection */
        $collection = $this->fileCollectionFactory->create();

        $collection->addAttributeToFilter('file_id', ['in' => array_keys($shownfilesIds)])
            ->addAttributeToSelect('path')
            ->addAttributeToSelect(['file_id', 'name', 'is_active', 'parent_id', 'level'])
        ;

        $fileById = [
            FileModel::TREE_ROOT_ID => [
                'value'    => FileModel::TREE_ROOT_ID,
                'level'    => 0,
                'optgroup' => null,
            ],
        ];

        foreach ($collection as $file) {
            $fileId = (int)$file->getId();
            $parentFileId = (int)$file->getParentId();
            foreach ([$fileId, $parentFileId] as $id) {
                if (!isset($fileById[$id])) {
                    $fileById[$id] = ['value' => $id, 'level' => 0];
                }
            }

            $fileById[$fileId]['is_active'] = $file->getIsActive();
            $fileById[$fileId]['label']     = $file->getName();
            $fileById[$fileId]['stores']    = $this->getStores($file);
            $fileById[$fileId]['level']     = $file->getLevel();

            if ($parentFileId) {
                if (
                    $fileById[$parentFileId]['level'] == $fileById[$fileId]['level'] - 1 ||
                    $collection->getItemById($parentFileId)->getLevel() == $fileById[$fileId]['level'] - 1
                ) {
                    $fileById[$parentFileId]['optgroup'][] = &$fileById[$file->getId()];
                }
            }
        }

        $this->filesTrees[$filter] = $fileById[FileModel::TREE_ROOT_ID]['optgroup'];

        return $this->filesTrees[$filter];
    }
}