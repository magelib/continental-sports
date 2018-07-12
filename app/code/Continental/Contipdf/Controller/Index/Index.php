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
    private $basepath;
    private $content;
    private $footerFile;
    private $headerFile;

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

	    $this->basepath = $_SERVER['DOCUMENT_ROOT'] . '/';
    }

    private function debug($str) {
        error_log(date("Y-m-d H:i:s ") . $str . PHP_EOL, 3, '/var/www/continental-staging/pdf-debug.log');
    }

    function execute()
    {
//        ob_start();
        $productId = isset($_GET['id']) ? preg_replace('/[^0-9]/', '', $_GET['id']) : false;

        if (!$productId) {
//            exit("<script>history.back();</script>");
            $this->debug("No product");
            exit("No product id");
        }

        $pdfheader = isset($_GET['headerhtml']) ? true : false; 
        
        $html = isset($_GET['html']) ? true : false;

        if ($pdfheader) {
            echo $this->getHeaderHtml();
            exit();
        } elseif ($html) {
            $this->createHtml($productId);
            $this->displayHtml($productId);
            exit();
        } else {
            $this->buildPdf($productId);
        // Force Download
            $this->ForceDownload();
            exit();
        }
    }

    private function ForceDownload() {
        ob_end_clean();
        header('Content-Type: application/pdf');
        header("Content-Transfer-Encoding: binary");
        header('Pragma: public');
        header("Content-disposition: attachment; filename=" . $this->pdfFilename);
        readfile($this->pdfFile);
    }

    /**
     * [displayHtml description]
     * @return [type] [description]
     */
    private function displayHtml($productId = null) {
        if ($productId === null) {
            exit("Missing product id");
        }

        $htmlFile = $this->basepath . 'pdf_html/'. $productId . '.html';
        echo file_get_contents($htmlFile);
    }

    /**
     * [buildPdf description]
     * @return [type] [description]
     */
    private function buildPdf($productId = null) {
    # Create html and save to file
        if ($productId === null ) exit("Missing Product id");
        $this->createHtml($productId);
    # Force Download of Pdf
        $this->debug("html created");
        $product = $this->getProduct($productId);
        $this->debug('product set');

        $filename = str_replace(' ', '_', $product->getName());
        $filename = str_replace(' - ', '-', $filename);
        $filename = preg_replace('/[^A-Za-z0-9_-]/', '', $filename);
        $filename .= '.pdf';
        $this->pdfFilename = $filename;
        $header_file = 'http://staging.continentalsports.co.uk/pdf/header.html';
        $footer_file = 'http://staging.continentalsports.co.uk/pdf/footer.html';
        
        # folder path to save pdf to
        $path = $this->basepath . 'media/pdf/';
        
        # change this back when working
        //if (!file_exists($path . $this->pdfFilename)) {
        # Path to html file on open port 80 pdf directory
            $htmlUrl = $_SERVER['HTTP_HOST'] . '/pdf/' . $productId . '.html';
        
        # Terminal command to compile the pdf
            $this->pdfFile =  $path . $filename;
            $header     = '--header-html \''. $header_file .'\' --header-spacing 5';
            $footer     = '--footer-html \''. $footer_file .'\' --footer-spacing 2 ';
            $htmlFile   = '\'' . $htmlUrl . '\'';
            $pdfFile    = '\'' . $this->pdfFile . '\'';
            $command    = '/usr/bin/xvfb-run -a --server-args="-screen 0, 1024x768x24" wkhtmltopdf '. 
            $header . ' ' .
            $footer .
            ' --margin-top 28mm --margin-left 0mm --margin-right 0mm --margin-bottom 20mm ' .
            $htmlFile . ' ' . 
            $pdfFile;

            $this->debug("Run command: " . $command);
            $bash = exec($command);
        //}
    }

    /**
     * [getFooter description]
     * @return [type] [description]
     */
    private function getFooter() {
        $this->checkFooter();
        if (file_exists($this->footerFile)) {
            return file_get_contents($this->footerFile);
        }
    }

    /**
     * [getHeader description]
     * @return [type] [description]
     */
    private function getHeader() {
        $this->checkHeader();
        if (file_exists($this->headerFile)) {
            
            return file_get_contents($this->headerFile);
        }
    }

    /**
     * [checkFooter description]
     * @return [type] [description]
     */
    private function checkFooter() {
        $this->footerFile = $_SERVER['DOCUMENT_ROOT'] . '/pdf_html/footer.html';

        if (!file_exists($this->footerFile)) {
            $footerHtml = <<< html
            <!DOCTYPE html>
           <body>
        <div class="pdf-footer" style="height:200px; text-align: center; font-size: 12px;">
            <p>Continental Sports Limited. Hill Top Road, Paddock, Huddersfield, West Yorkshire HD1 4SD. Registered in England &amp; Wales No: 00830200. VAT No: 516 3500 76</p>
			<p>Telephone: 01484 542051   |   Fax: 01484 539148   |   Email: sales@contisports.co.uk</p>
        </div>
        </body>
        </html>
html;
            file_put_contents($this->footerFile, $footerHtml);
        }

    }

    /**
     * [getHeaderHtml description]
     * @return [type] [description]
     */
    private function getHeaderHtml() {
            $server = 'http://' . $_SERVER['SERVER_NAME'];
            $html = <<< html
                      <!DOCTYPE html>
           <head>
            <link href="//fonts.googleapis.com/css?family=Montserrat|Roboto" rel="stylesheet">
            <style>
            body {
                margin:0;padding:0;
                font-family: 'Roboto', sans-serif;
                color:#fff;
            }
            .pdf-container {
                padding:30px
            }
            .pdf-header {
                background-color:#333!important; height:100px;
            }
           </style>
           </head>
           <body>
           <div class="pdf-header">
                <div class="pdf-container">
                    <img src="{$server}/pdf/images/footer-logo.png" alt="PDF Logo" />
                </div>
            </div>
            </body>
            </html>
html;
                return $html;
    }

    /**
     * [checkHeader description]
     * @return [type] [description]
     */
    private function checkHeader() {
        $this->headerFile = $_SERVER['DOCUMENT_ROOT'] . '/pdf_html/header.html';
        $site = $_SERVER['SERVER_NAME'];    
        if (!file_exists($this->headerFile)) {
            $headerHtml = $this->getHeaderHtml();
            file_put_contents($headerFile, $headerHtml);
        }
    }
    
    /**
     * [createHtml description]
     * @param  boolean $productId [description]
     * @return [type]             [description]
     */
    private function createHtml($productId = false)
    {
        $this->debug("createHtml()");
        // Added header
        
        $this->checkHeader();

        // Added Footer
        $this->checkFooter();
        # Get product id
        
        if ($productId === false) return false;

        # Get template

	    $template = dirname(__FILE__) . '/../../view/frontend/templates/pdf_template.phtml';

        if (!file_exists($template)) exit("Cannot open template: $template");//return false; # Log missing template
        $contents = file_get_contents($template);

        # Get product details
        $product = $this->getProduct($productId);

	   // Only show sku master for simple products
	   $skuMaster = ($this->showConfigurablesCount($product)) ? '' : $product->getSku();
       
       // copy main image
       $fileParts = explode('/', $product->getImage() );
       $imageFileName = end( $fileParts  );
       
       $newImageFile = $_SERVER['DOCUMENT_ROOT'] . '/pdf_html/images/'. $imageFileName;
       // Copy image files across to port 80 folder
       $command = 'cp '. $_SERVER['DOCUMENT_ROOT'] . '/media/catalog/product' . $product->getImage() . ' ' . $newImageFile;
       $this->debug($command); 
       system( $command );

       // Set permissions
       system( 'chown www-data ' . $newImageFile); 
        $replacements = array(
            'description' => $product->getDescription(),
            'title' => $product->getName(),
            'sku-master' => $skuMaster,
            'mainimage' => 'http://' . $this->get_server() . '/pdf/images/' . $imageFileName
        );

        # Populate template
        foreach ($replacements as $tag => $value) {
            $contents = str_replace('{%' . $tag . '%}', $value, $contents);
        }

        $configurableArray = $this->getConfigurables($productId, $product, $contents);

        $contents = str_replace($configurableArray[0], $configurableArray[1], $contents);
        // Save the contents as an html file
        $this->saveHtml( $contents, $productId );
    }

    function saveHtml( $contents, $productId ) {
        $this->debug("saveHtml");
        ob_start();
        # Show main html
        echo $contents;
        $html = ob_get_clean();

        # Add page break for options
        $html = str_replace( '<p>Dimensions:</p>', '<p style="page-break-before: always; padding-top:10px;">&nbsp;</p><p>Dimensions:</p>', $html );
        // write html to open port 80 directory so we can access with wkhtml
        $this->debug("$contents");
        
        $htmlfile = $this->get_path($productId);
        $this->debug("put contents to $htmlfile");
        $this->debug("put contents to $html");
        file_put_contents( $htmlfile, $html );
         $this->debug("saved");
    }

    function get_server() {
        return 'staging.continentalsports.co.uk';
    }
    function get_path($productId) {
        return '/var/www/continental-staging/pub/pdf_html/' . $productId . '.html'; 
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
        try {
            $r = array();
            if ($this->showConfigurablesCount($product)) {
                $pattern = '/<!-- [CONFIGURATION_START] -->.*<!-- [CONFIGURATION_END] -->/s';

                $pattern = '/<!-- \[CONFIGURATION_START\] -->.*END/s';
                $pattern = '/<!-- \[CONFIGURATION_START\] -->.*END\] -->/s';
                //$pattern = '/<!-- \[CONFIGURATION_START\] -->.*<!-- \[CONFIGURATION_END\] -->/';
                $replacement = '';

                preg_match($pattern, $content, $matches);
                if (empty($matches[0])) {
                    exit(print_r($matches, true));
                }

                $template = $matches[0];

                foreach ($this->configurablesData($product) as $index => $row) {
                    $colour = $row->getAttribute('color');
                    $colour = !empty($colour) ? $colour : 'N/A';
                    $replacement .= str_replace(
                        array('{%sku%}', '{%options%}', '{%colour%}'),
                        array($row->getSku(), $row->getName(), $colour),
                        $template);
                }

                $r[1] = 'SharkFins';
            } else {
                $pattern = '/<table>.*<\/table>/';
                preg_match($pattern, $content, $matches);

                $template = isset($matches[0]) ? $matches[0] : '';
            }
            $r[0] = $template;
            $r[1] = isset($replacement) ? $replacement : '';
        } catch (\Exception $e) {
//            die($e->getMessage());
        }
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
}
