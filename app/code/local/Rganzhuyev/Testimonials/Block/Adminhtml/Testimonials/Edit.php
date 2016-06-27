<?php

class Rganzhuyev_Testimonials_Block_Adminhtml_Testimonials_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'rg_testimonials';
        $this->_controller = 'adminhtml_testimonials';

        $this->_updateButton('save', 'label', Mage::helper('rg_testimonials')->__('Save Testimonial'));
        $this->_updateButton('save', 'id', 'save_button');
        $this->_updateButton('delete', 'label', Mage::helper('rg_testimonials')->__('Delete Testimonial'));

        if( $this->getRequest()->getParam($this->_objectId) ) {
            $reviewData = Mage::getModel('rg_testimonials/testimonial')
                ->load($this->getRequest()->getParam($this->_objectId));
            Mage::register('testimonial_data', $reviewData);
        }
    }

    public function getHeaderText()
    {
        if( Mage::registry('testimonial_data') && Mage::registry('testimonial_data')->getId() ) {
            return Mage::helper('rg_testimonials')->__("Edit Testimonial '%s'", $this->htmlEscape(Mage::registry('testimonial_data')->getTitle()));
        } else {
            return Mage::helper('rg_testimonials')->__('New Testimonial');
        }
    }
}
