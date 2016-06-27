<?php
class Rganzhuyev_Testimonials_Model_Testimonial_Status
{
    const STATUS_PENDING    = 1;
    const STATUS_APPROVED   = 2;
    const STATUS_DECLINED   = 3;

    public function toOptionArray()
    {
        return array(
            self::STATUS_PENDING    => Mage::helper('rg_testimonials')->__('Pending'),
            self::STATUS_APPROVED   => Mage::helper('rg_testimonials')->__('Approved'),
            self::STATUS_DECLINED   => Mage::helper('rg_testimonials')->__('Declined'),
        );
    }
}