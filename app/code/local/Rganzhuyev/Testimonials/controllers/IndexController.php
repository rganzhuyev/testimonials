<?php
class Rganzhuyev_Testimonials_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->renderLayout();
    }

    public function submitAction()
    {
        if(!$this->_validateFormKey()) {
            $this->_getSession()->addError($this->__('Error happened'));
            $this->_redirectReferer();
        }

        if($this->getRequest()->isPost()) {
            try {
                if(!$this->_getSession()->isLoggedIn()) {
                    Mage::throwException($this->__('Only registered users can leave testimonials'));
                }
                /**
                 * @var $testimonial Rganzhuyev_Testimonials_Model_Testimonial
                 */
                $testimonial = Mage::getModel('rg_testimonials/testimonial');
                $testimonialData = $this->getRequest()->getPost('testimonial');
                $testimonial->setData($testimonialData);
                $testimonial->setData('customer_id', $this->_getSession()->getCustomerId())
                    ->setData('store_id', Mage::app()->getStore()->getId())
                ;
                if(!$testimonial->save()) {
                    Mage::throwException(implode("\n", $testimonial->getValidationErrors()));
                }
                $this->_getSession()->addSuccess($this->__('Your review has been successfully submitted'));
            } catch(Mage_Core_Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError(str_replace("\n", '<br />', $e->getMessage()));
            } catch(Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError($this->__('Error happened'));
            }
        }
        $this->_redirectReferer();
    }

    /**
     * @return Mage_Customer_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }
}