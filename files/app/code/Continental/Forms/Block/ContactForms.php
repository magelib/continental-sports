<?php
namespace Continental\Forms\Block;

/**
* Features block
*/

// use Magento\Customer\Model\Url;
// use Magento\Framework\App\Http\Context;
// use Magento\Framework\View\Element\Template;

use Magento\Framework\View\Element\Template;

class ContactForms extends \Magento\Contact\Block\ContactForm
{

    public function __construct(
            Template\Context $context,
            \Magento\Directory\Block\Data $directoryBlock, 
            array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->_isScopePrivate = true;
        $this->directoryBlock = $directoryBlock;
    }

    public function getCountries()
    {
        $country = $this->directoryBlock->getCountryHtmlSelect();
        return $country;
    }
    public function getRegion()
    {
        $region = $this->directoryBlock->getRegionHtmlSelect();
        return $region;
    }
}
}
