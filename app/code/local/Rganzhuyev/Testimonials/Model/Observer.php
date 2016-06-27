<?php
class Rganzhuyev_Testimonials_Model_Observer
{
    public function addTestimonialMenuItem(Varien_Event_Observer $observer)
    {
        $menu = $observer->getData('menu');
        $tree = $menu->getTree();

        /**
         * @var $menu Varien_Data_Tree_Node
         * @var $tree Varien_Data_Tree
         * @var $menuItem Varien_Data_Tree_Node
         */

        $menuItemData = array(
            'name'      => Mage::helper('rg_testimonials')->__('Testimonials'),
            'id'        => 'testimonial-page',
            'url'       => Mage::getUrl('rg_testimonials'),
            'is_active' => 1,
        );
        $menuItem = new Varien_Data_Tree_Node($menuItemData, 'id', $tree, $menu);
        $menu->addChild($menuItem);


        return $this;
    }
}