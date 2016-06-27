<?php

class Rganzhuyev_Testimonials_Block_Adminhtml_Testimonials_Main extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_addButtonLabel = Mage::helper('rg_testimonials')->__('Add New Testimonial');
        parent::__construct();

        $this->_blockGroup = 'rg_testimonials';
        $this->_controller = 'adminhtml_testimonials';

        if( Mage::registry('usePendingFilter') === true ) {
            $this->_headerText = Mage::helper('rg_testimonials')->__('Pending Testimonial');
        } else {
            $this->_headerText = Mage::helper('rg_testimonials')->__('All Testimonials');
        }
        $this->_removeButton('add');

    }
}
