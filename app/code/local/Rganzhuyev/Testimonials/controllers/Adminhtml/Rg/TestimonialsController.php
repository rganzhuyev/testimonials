<?php
class Rganzhuyev_Testimonials_Adminhtml_Rg_TestimonialsController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->_title($this->__('Testimonials'))
            ->_title($this->__('Testimonials'))
            ->_title($this->__('Testimonials'));

        $this->_title($this->__('All Testimonials'));

        $this->loadLayout();
        $this->_setActiveMenu('catalog/review');

        $this->renderLayout();
    }

    public function pendingAction()
    {
        $this->_title($this->__('Testimonials'))
            ->_title($this->__('Testimonials'));

        $this->_title($this->__('Pending Testimonials'));


        Mage::register('usePendingFilter', true);
        $this->loadLayout();
        $this->_setActiveMenu('catalog/review');

        $this->renderLayout();
    }

    public function editAction()
    {
        $this->_title($this->__('Testimonials'))
            ->_title($this->__('Testimonials'));

        $this->_title($this->__('Edit Testimonial'));

        $this->loadLayout();
        $this->_setActiveMenu('catalog/review');

        $this->_addContent($this->getLayout()->createBlock('rg_testimonials/adminhtml_testimonials_edit'));

        $this->renderLayout();
    }
    
    public function saveAction()
    {
        if (($data = $this->getRequest()->getPost())) {
            $this->_getSession()->setFormData($data);
            $testimonialId = $this->getRequest()->getParam('id');
            $testimonial = Mage::getModel('rg_testimonials/testimonial');
            if($testimonialId) {
                $testimonial->load($testimonialId);
            }
            $session = Mage::getSingleton('adminhtml/session');
            if ($testimonialId && ! $testimonial->getId()) {
                $session->addError(Mage::helper('rg_testimonials')->__('The testimonial was removed by another user or does not exist.'));
            } else {
                try {
                    $testimonial->addData($data);
                    if(!$testimonial->save()) {
                        Mage::throwException(implode("\n", $testimonial->getValidationErrors()));
                    }
                    $this->_getSession()->unsFormData();
                    $session->addSuccess(Mage::helper('rg_testimonials')->__('The testimonial has been saved.'));
                    $this->getResponse()->setRedirect($this->getUrl('*/*'));
                    return true;
                } catch (Mage_Core_Exception $e) {
                    $session->addError(str_replace("\n", '<br />', $e->getMessage()));
                } catch (Exception $e){
                    $session->addException($e, Mage::helper('rg_testimonials')->__('An error occurred while saving this testimonial.'));
                }
            }

            return $this->getResponse()->setRedirect($this->getUrl('*/*/new'));
        }
        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        $testimonialId   = $this->getRequest()->getParam('id', false);
        $session    = Mage::getSingleton('adminhtml/session');

        try {
            Mage::getModel('rg_testimonials/testimonial')->setId($testimonialId)
                ->delete();

            $session->addSuccess(Mage::helper('rg_testimonials')->__('The testimonial has been deleted'));
            $this->getResponse()->setRedirect($this->getUrl('*/*/'));
            return;
        } catch (Mage_Core_Exception $e) {
            $session->addError($e->getMessage());
        } catch (Exception $e){
            $session->addException($e, Mage::helper('rg_testimonials')->__('An error occurred while deleting this testimonial.'));
        }

        $this->_redirect('*/*/edit/',array('id'=>$testimonialId));
    }

    public function massDeleteAction()
    {
        $testimonialsIds = $this->getRequest()->getParam('testimonials');
        $session    = Mage::getSingleton('adminhtml/session');

        if(!is_array($testimonialsIds)) {
            $session->addError(Mage::helper('rg_testimonials')->__('Please select testimonial(s).'));
        } else {
            try {
                foreach ($testimonialsIds as $testimonialId) {
                    $model = Mage::getModel('rg_testimonials/testimonial')->load($testimonialId);
                    $model->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('rg_testimonials')->__('Total of %d record(s) have been deleted.', count($testimonialsIds))
                );
            } catch (Mage_Core_Exception $e) {
                $session->addError($e->getMessage());
            } catch (Exception $e){
                $session->addException($e, Mage::helper('rg_testimonials')->__('An error occurred while deleting record(s).'));
            }
        }

        $this->_redirect('*/*/');
    }

    public function massUpdateStatusAction()
    {
        $testimonialsIds = $this->getRequest()->getParam('testimonials');
        $session    = Mage::getSingleton('adminhtml/session');

        if(!is_array($testimonialsIds)) {
            $session->addError(Mage::helper('rg_testimonials')->__('Please select testimonial(s).'));
        } else {
            /* @var $session Mage_Adminhtml_Model_Session */
            try {
                $status = $this->getRequest()->getParam('status');
                foreach ($testimonialsIds as $testimonialId) {
                    $model = Mage::getModel('rg_testimonials/testimonial')->load($testimonialId);
                    $model->setStatus($status)
                        ->save();
                }
                $session->addSuccess(
                    Mage::helper('rg_testimonials')->__('Total of %d record(s) have been updated.', count($testimonialsIds))
                );
            } catch (Mage_Core_Exception $e) {
                $session->addError($e->getMessage());
            } catch (Exception $e) {
                $session->addException($e, Mage::helper('rg_testimonials')->__('An error occurred while updating the selected testimonial(s).'));
            }
        }

        $this->_redirect('*/*/');
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('catalog/reviews_ratings/testimonials');
    }
}