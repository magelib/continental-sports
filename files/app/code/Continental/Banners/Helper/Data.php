<?php
/**
 * @author Continental Team
 * @copyright Copyright (c) 2017 Continental 
 * @package Continental_Banners
 */
namespace Continental\Banners\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    CONST ENABLE      = 'Continental_Banners/general/enable';
    CONST BLOCK_LABEL = 'Continental_Banners/general/block_label';
    CONST TEXT_ALIGN  = 'Continental_Banners/general/text_align';
    CONST BLOCK_TITLE = 'Continental_Banners/general/block_title';
    CONST BLOCK_TEXT = 'Continental_Banners/general/block_text';
    CONST BLOCK_HREF = 'Continental_Banners/general/block_href';
    CONST BLOCK_BACK = 'Continental_Banners/general/background_image';

    public function __construct(
        \Magento\Framework\App\Helper\Context $context
     ){
        parent::__construct($context);
        $this->_scopeConfig = $this->scopeConfig;
    }

    public function getEnable(){
        return $this->_scopeConfig->getValue(self::ENABLE);
    }

    public function getBlockLabel(){
        return $this->_scopeConfig->getValue(self::BLOCK_LABEL);
    }

    public function getTextAlign(){
        return $this->_scopeConfig->getValue(self::TEXT_ALIGN);
    }
 
    public function getBlockBack(){
        return $this->_scopeConfig->getValue(self::BLOCK_BACK);
    }

    public function getBlockHref(){
        return $this->_scopeConfig->getValue(self::BLOCK_HREF);
    }

    public function getBlockText(){
        return $this->_scopeConfig->getValue(self::BLOCK_TEXT);
    }

    public function getBlockTitle(){
        return $this->_scopeConfig->getValue(self::BLOCK_TITLE);
    }
}
