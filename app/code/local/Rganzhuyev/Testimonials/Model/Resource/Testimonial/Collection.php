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

    protected function _joinCustomer($select=null)
    {
        $firstNameAttribute = $this->_getCustomerResource()->getAttribute('firstname');
        $lastNameAttribute = $this->_getCustomerResource()->getAttribute('lastname');

        if(!is_object($select) || !($select instanceof Varien_Db_Select)) {
            $select = $this->getSelect();
        }

        $select
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
            )
        ;
    }

    public function getSelectCountSql()
    {
        $countSelect = parent::getSelectCountSql();
        if(Mage::app()->getStore()->getCode() != Mage_Core_Model_Store::ADMIN_CODE) {
            $countSelect->reset(Varien_Db_Select::INNER_JOIN);
            $this->_joinCustomer($countSelect);
            $countSelect->reset(Zend_Db_Select::COLUMNS);
            $countSelect->columns('COUNT(*)');
        }

        return $countSelect;
    }


    /**
     * @return Mage_Customer_Model_Resource_Customer|Mage_Core_Model_Abstract
     */
    protected function _getCustomerResource()
    {
        return Mage::getModel('customer/customer')->getResource();
    }
}