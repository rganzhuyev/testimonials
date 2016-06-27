<?php
class Rganzhuyev_Testimonials_Block_Adminhtml_Testimonials_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('testimonialsGrid');
        $this->setDefaultSort('added_time');
    }

    protected function _prepareCollection()
    {
        /**
         * @var $model Rganzhuyev_Testimonials_Model_Testimonial
         * @var $collection Rganzhuyev_Testimonials_Model_Resource_Testimonial_Collection
         */
        $model = Mage::getModel('rg_testimonials/testimonial');
        $collection = $model->getCollection();

        if(Mage::registry('usePendingFilter')) {
            $collection->addFieldToFilter('status', Rganzhuyev_Testimonials_Model_Testimonial_Status::STATUS_PENDING);
        }

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $statuses = Mage::getModel('rg_testimonials/testimonial_status')->toOptionArray();

        $this->addColumn('entity_id', array(
            'header'        => Mage::helper('rg_testimonials')->__('ID'),
            'align'         => 'right',
            'width'         => '50px',
            'index'         => 'entity_id',
        ));

        $this->addColumn('title', array(
            'header'        => Mage::helper('rg_testimonials')->__('Title'),
            'align'         => 'left',
            'width'         => '100px',
            'index'         => 'title',
            'type'          => 'text',
            'truncate'      => 50,
            'escape'        => true,
        ));

        $this->addColumn('text', array(
            'header'        => Mage::helper('rg_testimonials')->__('Text'),
            'align'         => 'left',
            'index'         => 'text',
            'type'          => 'text',
            'truncate'      => 50,
            'nl2br'         => true,
            'escape'        => true,
        ));

        $this->addColumn('username', array(
            'header'        => Mage::helper('rg_testimonials')->__('Username'),
            'align'         => 'left',
            'width'         => '100px',
            'index'         => 'username',
            'type'          => 'text',
            'escape'        => true,
        ));

        $this->addColumn('created_at', array(
            'header'        => Mage::helper('rg_testimonials')->__('Created At'),
            'align'         => 'left',
            'type'          => 'datetime',
            'width'         => '100px',
            'index'         => 'created_at',
        ));

        if( !Mage::registry('usePendingFilter') ) {
            $this->addColumn('status', array(
                'header'        => Mage::helper('rg_testimonials')->__('Status'),
                'align'         => 'left',
                'type'          => 'options',
                'options'       => $statuses,
                'width'         => '100px',
                'index'         => 'status',
            ));
        }

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('visible_in', array(
                'header'    => Mage::helper('rg_testimonials')->__('Visible In'),
                'index'     => 'store_id',
                'type'      => 'store',
                'store_view' => true,
            ));
        }

        $this->addColumn('action',
            array(
                'header'    => Mage::helper('adminhtml')->__('Action'),
                'width'     => '50px',
                'type'      => 'action',
                'getter'     => 'getEntityId',
                'actions'   => array(
                    array(
                        'caption' => Mage::helper('adminhtml')->__('Edit'),
                        'url'     => array(
                            'base'=>'*/rg_testimonials/edit',
                        ),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false
            ));

        return parent::_prepareColumns();
    }

    protected function _addColumnFilterToCollection($column)
    {
        /**
         * @var $collection Mage_Customer_Model_Resource_Customer_Collection
         */
        if ($collection = $this->getCollection()) {
            $field = ( $column->getFilterIndex() ) ? $column->getFilterIndex() : $column->getIndex();
            $cond = $column->getFilter()->getCondition();

            if($field == 'username') {
                $collection->getSelect()
                    ->where('CONCAT(fnt.value, \' \', lnt.value) LIKE ?', $cond)
                ;
                return $this;
            }
        }
        return parent::_addColumnFilterToCollection($column);
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('testimonials');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'=> Mage::helper('rg_testimonials')->__('Delete'),
            'url'  => $this->getUrl(
                '*/*/massDelete'
            ),
            'confirm' => Mage::helper('rg_testimonials')->__('Are you sure?')
        ));

        $statuses = Mage::getModel('rg_testimonials/testimonial_status')->toOptionArray();
        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('update_status', array(
            'label'         => Mage::helper('rg_testimonials')->__('Update Status'),
            'url'           => $this->getUrl(
                '*/*/massUpdateStatus'
            ),
            'additional'    => array(
                'status'    => array(
                    'name'      => 'status',
                    'type'      => 'select',
                    'class'     => 'required-entry',
                    'label'     => Mage::helper('rg_testimonials')->__('Status'),
                    'values'    => $statuses
                )
            )
        ));
    }
}