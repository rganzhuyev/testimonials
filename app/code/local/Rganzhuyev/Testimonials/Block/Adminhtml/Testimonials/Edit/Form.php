<?php

class Rganzhuyev_Testimonials_Block_Adminhtml_Testimonials_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $testimonial = Mage::registry('testimonial_data');
        $statuses = Mage::getModel('rg_testimonials/testimonial_status')->toOptionArray();
        $mode = (is_object($testimonial) && intval($testimonial->getId()) > 0) ? 'edit' : 'add';

        $form = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'action'    => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
            'method'    => 'post'
        ));

        $fieldset = $form->addFieldset('testimonial_details', array('legend' => Mage::helper('rg_testimonials')->__('Testimonial Details'), 'class' => 'fieldset-wide'));

        $dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
        $fieldset->addField('created_at', 'date', array(
            'name'   => 'created_at',
            'time'   => true,
            'label'  => Mage::helper('rg_testimonials')->__('Created At Time/Date'),
            'title'  => Mage::helper('rg_testimonials')->__('Created At Time/Date'),
            'image'  => $this->getSkinUrl('images/grid-cal.gif'),
            'format'       => 'yyyy-MM-dd HH:mm:ss'
        ));


        $fieldset->addField('status', 'select', array(
            'label'     => Mage::helper('rg_testimonials')->__('Status'),
            'required'  => true,
            'name'      => 'status',
            'values'    => $statuses,
        ));

        /**
         * Check is single store mode
         */
        if (!Mage::app()->isSingleStoreMode()) {
            $field = $fieldset->addField('select_stores', 'select', array(
                'label'     => Mage::helper('rg_testimonials')->__('Visible In'),
                'required'  => true,
                'name'      => 'store_id',
                'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(),
            ));
            $renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
            $field->setRenderer($renderer);
            $testimonial->setSelectStores($testimonial->getStoreId());
        }
        else {
            $fieldset->addField('select_stores', 'hidden', array(
                'name'      => 'store_id',
                'value'     => Mage::app()->getStore(true)->getId()
            ));
            $testimonial->setSelectStores(Mage::app()->getStore(true)->getId());
        }

        $fieldset->addField('username', 'label', array(
            'label'     => Mage::helper('rg_testimonials')->__('Username'),
            'required'  => true,
            'name'      => 'username',
            'style'     => 'font-weight:bold'
        ));

        $fieldset->addField('title', 'text', array(
            'label'     => Mage::helper('rg_testimonials')->__('Testimonial title'),
            'required'  => true,
            'name'      => 'title',
        ));

        $fieldset->addField('text', 'textarea', array(
            'label'     => Mage::helper('rg_testimonials')->__('Testimonial'),
            'required'  => true,
            'name'      => 'text',
            'style'     => 'height:24em;',
        ));

        $form->setUseContainer(true);
        $form->setValues($mode == 'edit' ? $testimonial->getData() : $this->_getSession()->getFormData());
        $this->setForm($form);
        return parent::_prepareForm();
    }

    /**
     * Retrieve adminhtml session model object
     *
     * @return Mage_Adminhtml_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('adminhtml/session');
    }
}
