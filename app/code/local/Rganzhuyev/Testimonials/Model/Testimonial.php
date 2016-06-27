<?php
class Rganzhuyev_Testimonials_Model_Testimonial extends Mage_Core_Model_Abstract
{
    protected $_validationErrors = array();

    public function _construct()
    {
        return $this->_init('rg_testimonials/testimonial');
    }

    public function getActiveReviews()
    {
        return $this->getCollection()
            ->addFieldToFilter('status', Rganzhuyev_Testimonials_Model_Testimonial_Status::STATUS_APPROVED)
            ->setOrder('created_at', 'desc')
        ;
    }

    public function getAddedTime()
    {
        /**
         * @var $helper Rganzhuyev_Testimonials_Helper_Data
         */
        $addedTime = $this->_getData('created_at');
        $helper = Mage::helper('rg_testimonials');
        return $helper->convertTimeToDate($addedTime);
    }

    public function validate()
    {
        if(!Zend_Validate::is($this->getData('title'), 'NotEmpty')) {
            $this->_validationErrors[] = Mage::helper('rg_testimonials')->__('Wrong Title Data');
        }
        if(!Zend_Validate::is($this->getData('text'), 'NotEmpty')) {
            $this->_validationErrors[] = Mage::helper('rg_testimonials')->__('Wrong Text Data');
        }
        return (count($this->_validationErrors) === 0);
    }

    public function getValidationErrors()
    {
        return $this->_validationErrors;
    }

    public function save()
    {
        $this->_prepareData();
        if($this->validate()) {
            try {
                parent::save();
                return true;
            } catch(Mage_Core_Exception $e) {
                Mage::logException($e);
                Mage::throwException('Error happened while saving the testimonial');
                return false;
            }
        }
        return false;
    }

    protected function _prepareData()
    {
        return true;
    }

    protected function _beforeSave()
    {
        if(!$this->getData('created_at')) {
            $this->setData('created_at', Mage::getModel('core/date')->timestamp(time()));
        }
        
        return parent::_beforeSave();
    }
}