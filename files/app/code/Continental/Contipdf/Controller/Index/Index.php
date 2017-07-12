<?php

namespace Continental\Contipdf\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\Controller\ResultFactory;
use Staempfli\Pdf\Model\View\PdfResult;
use Staempfli\Pdf\Service\Pdf;
use Staempfli\Pdf\Service\PdfOptions;
use Staempfli\Pdf\Service\PdfFactory;
#use Continental\Contipdf;
class Index extends Action
{
    private $content;


    public function execute()
    {
	$filename = 'contitest.pdf';
//      $page = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
	$result = $this->resultFactory->create(PdfResult::TYPE);

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

/*	$result->addpageOptions(
		new PdfOptions(
		    [
			PdfOptions::KEY_PAGE_COOKIES => $_COOKIE,
		    ]
		)
	);
*/

//	$this->pdfFactory = new PdfFactory();
//	$this->pdf = $this->pdfFactory->create();
	// Try content?
//	$this->pdf->appendContent('Trampoline');
//	$result->renderPdf();
	return $result;

      //  return $page;
	echo "Hello PDF";
//	$this->pdf = this->_objectManager->create('staempfli_pdf/pdf');
//	$this->content = $this->pdf->createBlock('staempfli_pdf/pdf_content');
//	$this->buildContent();
	
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
