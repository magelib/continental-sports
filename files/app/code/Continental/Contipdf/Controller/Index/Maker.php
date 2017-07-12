<?php
/**
 * Created by PhpStorm.
 * User: MattB
 * Date: 06/07/2017
 * Time: 16:07
 */

//class Continental_Contipdf_Maker extends Staempfli_Pdf_Model_Pdf
class Maker extends Staempfli_Pdf
{
    private $title;
    private $cssPAth;
    private $header;
    private $footer;
    public $content;

    public function __construct()
    {
        $this->header = $this->createBlock('staempfli_pdf/pdf_header');
        $this->footer = $this->createBlock('staempfli_pdf/pdf_footer');
        $this->content = $this->createBlock('staempfli_pdf/pdf_content');
        $this->setPageSize('A4');
        $this->setOrientation('Portrait');
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
        $this->content->addStylesheet($this->cssPath . 'pdf.css');

        # PDF Title
        $this->content->setTitle($this->title);

        # Header
        $this->standardHeader();

        #H1 example
        $this->content->addContent('Product Page', 'h1', array('class' => 'custom'));

    }

    function getDatabaseItem() {

    }

    function basicTest() {
        echo "passed";
	}

}
