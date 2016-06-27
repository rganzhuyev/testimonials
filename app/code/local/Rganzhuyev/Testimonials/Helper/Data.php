<?php
class Rganzhuyev_Testimonials_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function convertTimeToDate($time)
    {
        /**
         * @var $dateModel Mage_Core_Model_Date
         */
        $dateModel = Mage::getModel('core/date');
        return $dateModel->date('d.m.Y', strtotime($time));
    }
}