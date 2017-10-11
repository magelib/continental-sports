<?php
namespace Continental\HomeFeatures\Block;

/**
* Features block
*/

class Features extends \Magento\Framework\View\Element\Template
{
    /***
     * @param \Magento\Framework\View\Element\Template\Context $context
     */
    public function __construct(\Magento\Framework\View\Element\Template\Context $context)
    {
        parent::__construct($context);
    }

    /***
     * Return Feature headings
     * @param $index
     * @return string
     */
    public function getTitle($index)
    {
       /* placeholder function until clarification with form */
        $features = array(
           "left" => "Technical",
           "right" => "Spares"
        );

        return isset($features[$index]) ? $features[$index] : '';
    }
}
