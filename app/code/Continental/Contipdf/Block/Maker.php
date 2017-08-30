<?php
namespace Continental\Contipdf\Block;

/**
*  Maker block
*/

use Magento\Customer\Model\Url;
use Magento\Framework\App\Http\Context;
use Magento\Framework\View\Element\Template;

class Maker extends \Magento\Framework\View\Element\Template
{

/** @var Url $_customerUrl */
    protected $_customerUrl;

    /** @var Context $httpContext */
    protected $httpContext;

    /**
     * Links constructor.
     * @param Template\Context $context
     * @param array $data
     * @param Url $customerUrl
     * @param Context $httpContext
     */
    public function __construct(Template\Context $context, Url $customerUrl, Context $httpContext, array $data)
    {
        $this->_customerUrl = $customerUrl;
        $this->httpContext = $httpContext;

        parent::__construct($context, $data);
    }

}
