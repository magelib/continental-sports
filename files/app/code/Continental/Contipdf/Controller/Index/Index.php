<?php

namespace Continental\Contipdf\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Staempfli\Pdf\Model\View\PdfResult;
use Staempfli\Pdf\Service\Pdf;
use Staempfli\Pdf\Service\PdfOptions;
use Staempfli\Pdf\Service\PdfFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\ConfigurableProduct\Api\LinkManagementInterface;

#use Continental\Contipdf;
class Index extends \Magento\Framework\App\Action\Action
{
    private $content;

    protected $productRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var LinkManagementInterface
     */
    protected $linkManagement;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        LinkManagementInterface $linkManagement,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
        $this->productRepository = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->linkManagement = $linkManagement;
        $this->directoryList = $directoryList;
        /**
         * Load the page defined in view/frontend/layout/samplenewpage_index_index.xml
         *
         * // return \Magento\Framework\View\Result\Page
         */

    }

    function execute()
    {
	ob_start();
        $productId = isset($_GET['id']) ? preg_replace('/[^0-9]/', '', $_GET['id']) : false;

        if (!$productId) {
            exit("<script>history.back();</script>");
        }

        $html = isset($_GET['html']) ? true : false;

        if ($html) {
            $this->createHtml($productId);
        } else {
            // Force Download of Pdf
            //result = $this->resultFactory->create(PdfResult::TYPE);
            $product = $this->getProduct($productId);
            $filename = str_replace(' ', '_', $product->getName());
            $filename = str_replace(' - ', '-', $filename);
            $filename = preg_replace('/[^A-Za-z0-9_-]/', '', $filename);
            $filename .= '.pdf';
            $path = '/var/www/html/pub/media/pdf/';
            if (!file_exists($path . $filename)) {
                $url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                $command = '/usr/bin/xvfb-run -a --server-args="-screen 0, 1024x768x24" wkhtmltopdf \'' . $url . '&html=yes\' \'' . $path . $filename . '\'';
                $bash = exec($command);
        } else {
                //echo " exists";
        }
	ob_end_clean();
        header('Content-Type: application/pdf');
	header("Content-Transfer-Encoding: binary");
	header('Pragma: public');
	header("Content-disposition: attachment; filename=".$filename);
	readfile($path.$filename);    

//exit($path.$filename);
            // Force Download
            //return $result;
        }
    }

    function getProtocol() {
        $isSecure = false;
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $isSecure = true;
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
            $isSecure = true;
        }
        return $isSecure ? 'https' : 'http';
    }

    function createHtml($productId = false)
    {
        # Get product id
        if ($productId === false) return false;

        # Get template
        $template = '/var/www/html/app/code/Continental/Contipdf/view/frontend/templates/pdf_template.phtml';
        if (!file_exists($template)) exit("Cannot open template");//return false; # Log missing template
        $contents = file_get_contents($template);

        //echo $contents;
        # Get product details
        $product = $this->getProduct($productId);

        $replacements = array(
            'description' => $product->getDescription(),
            'title' => $product->getName(),
            'sku-master' => $product->getSku(),
            'mainimage' => '/pub/media/catalog/product/' . $product->getImage()
        );

        # Populate template
        foreach ($replacements as $tag => $value) {
            $contents = str_replace('{%' . $tag . '%}', $value, $contents);
        }

        $configurableArray = $this->getConfigurables($productId, $product, $contents);

        $contents = str_replace($configurableArray[0], $configurableArray[1], $contents);

        # Show html
        echo $contents;
        exit();
//  	$result = $this->resultFactory->create(PdfResult::TYPE);

//   	return $result;
    }

    function _execute()
    {
        $filename = 'contitest.pdf';
        $result = $this->resultFactory->create(PdfResult::TYPE);
        $source = $result->renderSourceDocument();
        $this->pdf = new
        $this->pdf->appendContent($source);

        # Generate PDF:

        $pdfFileContents = $this->pdf->file()->toString();

        $result->setFilename($filename);

        $result->addGlobalOptions(
            new PdfOptions(
                [
                    PdfOptions::KEY_GLOBAL_TITLE => 'Example Title',
                    PdfOptions::KEY_PAGE_ENCODING => PdfOptions::ENCODING_UTF_8,
                    PdfOptions::KEY_GLOBAL_ORIENTATION => PdfOptions::ORIENTATION_LANDSCAPE,
                ]
            )
        );
        echo "Complete";
        return $result;

    }

    /***
     *  getConfirurables
     *  Checks for confirurable products and if found replaces template row with substituted values in each row
     * @param $productId
     * @param $product
     * @param $content
     * @return array
     */

    protected function getConfigurables($productId, $product, $content)
    {
        $r = array();
        if ($this->showConfigurablesCount($product)) {
            $pattern = '/<!-- [CONFIGURATION_START] -->.*<!-- [CONFIGURATION_END] -->/s';

            $pattern = '/<!-- \[CONFIGURATION_START\] -->.*END/s';
            $pattern = '/<!-- \[CONFIGURATION_START\] -->.*END\] -->/s';
            //$pattern = '/<!-- \[CONFIGURATION_START\] -->.*<!-- \[CONFIGURATION_END\] -->/';
            $replacement = '';

            preg_match($pattern, $content, $matches);
            if (empty($matches[0])) exit( print_r($matches, true) );

            $template = $matches[0];

            foreach ($this->configurablesData($product) as $index => $row) {
                $colour = $row->getAttribute('color');
                $colour = !empty($colour) ? $colour : 'N/A';
                $replacement .= str_replace(
                   array('{%sku%}','{%options%}','{%colour%}'),
                   array( $row->getSku(), $row->getName(), $colour ),
                   $template);
            }

            $r[1] = 'SharkFins';
        } else {
            $pattern = '/<table>.*<\/table>/';
            preg_match($pattern, $content, $matches);
            $template = $matches[0];
        }
        $r[0] = $template;
        $r[1] = $replacement;

        return $r;

    }

    public function getProduct($productId)
    {
        if (!is_numeric($productId)) return false;

        return $product = $this->productRepository->getById($productId);
    }


    public function showConfigurablesCount($product = null)
    {
        if ($product->getTypeId() == \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE) {
            $_children = $product->getTypeInstance()->getUsedProducts($product);
            $count = count($_children);
            return ($count > 0) ? $count : false;
        }
        return false;
    }


    protected function configurablesData($product = null)
    {
        $sku = $product->getsku();
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('type_id', 'configurable')
            ->create();

        $configurableProducts = $this->productRepository->getList($searchCriteria);

        foreach ($configurableProducts->getItems() as $configurableProduct) {
            $childProducts = $this->linkManagement->getChildren($configurableProduct->getSku());
            if ($sku === $configurableProduct->getSku()) {
                return $childProducts;
            }
        }
    }

    public function standardHeader()
    {
        $this->content->addSection('header');
        // here you can define your Content by ->addContent
        // or adding an Image with ->addImage()

        $this->content->addImage('http://continental.attercopia.co.uk/media/logo/stores/1/logo.png', 'Continental Sports');
        $this->content->endSection();
    }

    public function buildContent()
    {
        $this->title = 'PDF Title';
        $this->cssPath = '/skin/frontend/your_theme/default/css/';

        /* Add Content */
        #Css

        $this->content = $this->pdf->createBlock('staempfli_pdf/pdf_content');
        $this->content->addStylesheet($this->cssPath . 'pdf.css');

        exit("Stop2");
        # PDF Title
        $this->content->setTitle($this->title);

        # Header
        $this->standardHeader();

        echo "DONE";

    }
}
