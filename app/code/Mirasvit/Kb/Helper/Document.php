<?php
namespace Mirasvit\Kb\Helper;
use \Magento\Framework\App\Helper\AbstractHelper;

class Document {

    /***
     * @var \Mirasvit\Kb\Model\ResourceModel\Document\CollectionFactory
     */
    protected $documentFactory;

    /***
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resourceConnection;

    public function __construct(
        \Mirasvit\Kb\Model\ResourceModel\Document\CollectionFactory $documentFactory,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magento\Framework\UrlInterface $urlInterface
    )
    {
        $this->documentFactory = $documentFactory;
        $this->resourceConnection = $resourceConnection;
        $this->urlInterface = $urlInterface;
    }

    /*
     *   @TODO Refactor this to find bug in code - shouldn't be needed as we are using Magento's Model
     */

    public function getDocumentsWhere($arr = null) {
        $where = ' WHERE ';
        foreach ($arr as $col=>$query) {
            $where .= sprintf("`%s` %s %s AND ", $col, $query['operator'], $query['value'] );
        }
        $where = rtrim(' AND', $where);

        return $this->getDocuments($where);
    }

    public function getDocuments($where = null) {
        /*$c = $this->documentFactory->create();
        $c->getCollection();
        return false;*/

        $connection = $this->resourceConnection->getConnection();
        $documentTable = $this->resourceConnection->getTableName('mst_kb_documents');
        $sql = "SELECT * FROM " . $documentTable;//". WHERE id = " . $themeId . ";";
        $result = $connection->fetchAll($sql);
        return $result;
    }

    public function optionArray() {
        $arr = array();
        $x = $this->getDocuments();
        foreach ($x as $item) {
            $arr[] = array('value' => $item['document_id'], 'label' => $item['name']);
        }
        return $arr;
    }

    public function debugGetDocuments() {
        $x = $this->getDocuments();
        foreach ($x as $item) {
            echo json_encode($item);
        }
    }

    public function afterElementMultiSelect($values) {
        $html = <<<HTML
            <script>
	            require([
                    'jquery'
                ], function ($) {
                $("select[id=select_field]").val([{$values}]);
                });
                </script>
HTML;
        return $html;
    }

    public function uploadForm() {
        $url = $this->urlInterface->getUrl('kbase/article/upload');
        $html = <<<HTML
            <div id="ModalUploader">
                <div class="upload-wrapper" data-bind="scope: 'uploader'">
                <!-- ko template: getTemplate() --><!-- /ko -->
                </div>
            </div>

            <script type="text/x-magento-init">
            {
            ".upload-wrapper": {
               "Magento_Ui/js/core/app": {
                   "components": {
                       "uploader": {
                           "component": "Magento_Ui/js/form/element/file-uploader",
                           "template": "ui/form/element/uploader/uploader",
                           "allowedExtensions": ["png", "jpg", "jpeg", "gif", "pdf", "docx", "doc", "docx", "xls", "xlsx"],
                           "uploaderConfig": {
                                "url": "{$url}"
                           }
                        }
                    }
               }
             }
            }
            </script>
HTML;
        return $html;
    }

    public function afterDocs() {
     $js=   <<<HTML
                    <script>
	            require([
	            'jquery','Magento_Ui/js/modal/modal'
	            ], function ($, modal) {
                    var options = {
                        type: 'popup',
                        responsive: true,
                        innerScroll: true,
                        title: 'Upload documents',
                        buttons: [{
                            text: $.mage.__('Close'),
                            class: '',
                            click: function () {
                                this.closeModal();
                            }
                        }]
                    };
                    $(document).ready(function() {
                    var popup = modal(options, $('#ModalUploader'));
                    $("#upload").on('click', function () {
                        $("#ModalUploader").modal("openModal");
                    });
                    });
                });
                </script>
HTML;
        return $js;
    }

}