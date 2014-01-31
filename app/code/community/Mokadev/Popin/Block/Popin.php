<?php
/**
 * MOKADEV
 *
 * @category   Mokadev
 * @package    Mokadev_Popin
 * @author     Mohamed Kaïd <mohamed@mokadev.com>
 */

class Mokadev_Popin_Block_Popin extends Mage_Core_Block_Template
{
    protected $_path_active           = 'mokadev_popin/general/active';
    protected $_path_home_only        = 'mokadev_popin/general/home_only';
    protected $_path_cookie_lifetime  = 'mokadev_popin/general/cookie';
    protected $_path_block            = 'mokadev_popin/general/block';
    protected $_cookie_name           = 'mokadev_popin';
    protected $_blockToShow;


    /**
     * Check if current url is url for home page
     *
     * @return true
     */
    public function getIsHomePage()
    {
        return $this->getUrl('') == $this->getUrl('*/*/*', array('_current'=>true, '_use_rewrite'=>true));
    }

    /**
     * Return true if the popin have to be displayed
     * @return boolean
     */
    public function canDisplayPopin()
    {
        // Is not enabled
        if(!Mage::getStoreConfigFlag($this->_path_active)) {
            return false;
        }

        //only in home page and we are not in home page?
        if(Mage::getStoreConfigFlag($this->_path_home_only) && !$this->getIsHomePage()) {
            return false;
        }

        //no block configured?
        if(!$this->getBlockToShow()) {
            return false;
        }

        // OK: Display popin if not already seen
        $cookieLifetime = Mage::getStoreConfig($this->_path_cookie_lifetime);
        $alreadySeenPopin = Mage::getSingleton('core/cookie')->get($this->_cookie_name);
        if(!$alreadySeenPopin) {
            $cookie = Mage::getSingleton('core/cookie')->setLifetime($cookieLifetime);
            $cookie->set($this->_cookie_name, 1);
            return true;
        }

        return false;
    }

    /**
     * return the block identifier to show in the popin
     */
    public function getBlockToShow()
    {
        if (is_null($this->_blockToShow)){
            $this->_blockToShow = Mage::getStoreConfig($this->_path_block);
        }

        return $this->_blockToShow;
    }

    /**
     * Render block HTML
     * @return boolean | string
     */
    public function getPopinContent()
    {
        if ($this->canDisplayPopin()){
            $blockId = $this->getBlockToShow();
            $block = Mage::getModel('cms/block')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($blockId);
            if ($block->getIsActive()) {
                /* @var $helper Mage_Cms_Helper_Data */
                $helper = Mage::helper('cms');
                $processor = $helper->getBlockTemplateProcessor();
                $html = $processor->filter($block->getContent());
                return $html;
            }
        }

        return false;
    }

}