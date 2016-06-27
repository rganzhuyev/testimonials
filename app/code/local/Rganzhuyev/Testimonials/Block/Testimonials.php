<?php
class Rganzhuyev_Testimonials_Block_Testimonials extends Mage_Core_Block_Template
{
    protected $_collection;
    protected $_pageBlock;

    public function isCustomerLoggedIn()
    {
        return $this->_getCustomerSession()->isLoggedIn();
    }

    /**
     * @return Rganzhuyev_Testimonials_Model_Resource_Testimonial_Collection
     */
    public function getCollection()
    {
        if($this->_collection === null ) {
            $this->_collection = $this->_getTestimonialModel()->getActiveReviews();
        }
        return $this->_collection;
    }

    public function getSubmitUrl()
    {
        return Mage::getUrl('rg_testimonials/index/submit');
    }


    public function getPagerBlock()
    {
        /**
         * @var $block Mage_Page_Block_Html_Pager
         */
        if($this->_pageBlock === null) {
            $block = $this->getLayout()->createBlock('page/html_pager', 'testimonials_pager');
            $block->setCollection($this->getCollection());
            $this->_pageBlock = $block;
        }
        return $this->_pageBlock;
    }

    /**
     * Retrieve list toolbar HTML
     *
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getPagerBlock()->toHtml();
    }

    /**
     * @return Rganzhuyev_Testimonials_Model_Testimonial|Mage_Core_Model_Abstract
     */
    protected function _getTestimonialModel()
    {
        return Mage::getModel('rg_testimonials/testimonial');
    }

    /**
     * @return Mage_Customer_Model_Session
     */
    protected function _getCustomerSession()
    {
        return Mage::getSingleton('customer/session');
    }
}