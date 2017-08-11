<?php

namespace Continental\Contipdf\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\Controller\ResultFactory;
use Staempfli\Pdf\Model\View\PdfResult;
use Staempfli\Pdf\Service\Pdf;
use Staempfli\Pdf\Service\PdfOptions;
use Staempfli\Pdf\Service\PdfFactory;
#use Continental\Contipdf;
class Index extends \Magento\Framework\App\Action\Action 
{
    private $content;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);

    /**
     * Load the page defined in view/frontend/layout/samplenewpage_index_index.xml
     *
     * // return \Magento\Framework\View\Result\Page
     */

   }

   function execute() {
	$productId = isset($_GET['id']) ? preg_replace('/[^0-9]/','', $_GET['id']) : false;

	if (!$productId) {
		exit("<script>history.back();</script>");
	}

	$html = isset($_GET['html']) ? true : false;

	if ($html) {
		$this->createHtml($productId);
	} else {
	// Force Download of Pdf
	}
   }

	function createHtml($productId = false) {
	# Get product id
	if ($productId === false) return false;
	
	# Get template
        $template = '/var/www/html/app/design/frontend/Attercopia/continental/Magento_Catalog/templates/product/productpdf.phtml';
	if (!file_exists($template)) exit("Cannot open template");//return false; # Log missing template
	$contents = file_get_contents($template);
	
	echo $contents;
	# Get product details

	# Populate template

	# Show html

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
		    PdfOptions::KEY_GLOBAL_TITLE          => 'Example Title',
   		    PdfOptions::KEY_PAGE_ENCODING         => PdfOptions::ENCODING_UTF_8,
		    PdfOptions::KEY_GLOBAL_ORIENTATION    => PdfOptions::ORIENTATION_LANDSCAPE,
		]
		)
	);
	echo "Complete";
	return $result;

    }

public function standardHeader() {
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
