<?php
class Rganzhuyev_Testimonials_Model_Resource_Testimonial extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        return $this->_init('rg_testimonials/testimonial', 'entity_id');
    }
}