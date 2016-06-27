<?php
class Rganzhuyev_Testimonials_Model_Resource_Testimonial_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    public function _construct()
    {
        return $this->_init('rg_testimonials/testimonial');
    }

    public function _beforeLoad()
    {
        $this->_joinCustomer();
        return parent::_beforeLoad();
    }

    protected function _joinCustomer()
    {
        $firstNameAttribute = $this->_getCustomerResource()->getAttribute('firstname');
        $lastNameAttribute = $this->_getCustomerResource()->getAttribute('lastname');


        $this
            ->getSelect()
            ->columns(array(
                'username' => new Zend_Db_Expr("CONCAT(fnt.value, ' ', lnt.value)")
            ))
            ->joinInner(
                array('fnt' => $firstNameAttribute->getBackendTable()),
                'fnt.entity_id=main_table.customer_id AND fnt.attribute_id='.$firstNameAttribute->getAttributeId(),
                null
            )
            ->joinInner(
                array('lnt' => $lastNameAttribute->getBackendTable()),
                'lnt.entity_id=main_table.customer_id AND lnt.attribute_id='.$lastNameAttribute->getAttributeId(),
                null
            );
    }

    /**
     * @return Mage_Customer_Model_Resource_Customer|Mage_Core_Model_Abstract
     */
    protected function _getCustomerResource()
    {
        return Mage::getModel('customer/customer')->getResource();
    }
}