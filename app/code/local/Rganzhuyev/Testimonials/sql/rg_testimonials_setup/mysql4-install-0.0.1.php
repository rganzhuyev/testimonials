<?php
/**
 * @var $this Mage_Core_Model_Resource_Setup
 */
$installer = $this;

$installer->startSetup();

/**
 * Create table 'rg_testimonials/testimonial' (`rg_testimonials_testimonial`)
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('rg_testimonials/testimonial'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Testimonial ID')
    ->addColumn('title', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
    ), 'Testimonial Title')
    ->addColumn('text', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
    ), 'Testimonial Text')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIME, null, array(
    ), 'Testimonial Time')
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_SMALLINT, 1, array(
        'unsigned'  => true,
        'default' => '1'
    ), 'Testimonial Status')
    ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
    ), 'Customer Id')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
    ), 'Store Id')
    ->addIndex($installer->getIdxName('rg_testimonials/testimonial', array('created_at')),
        array('created_at'))
    ->addIndex($installer->getIdxName('rg_testimonials/testimonial', array('status')),
        array('status'))
    ->addIndex($installer->getIdxName('rg_testimonials/testimonial', array('customer_id')),
        array('customer_id'))
    ->addIndex($installer->getIdxName('rg_testimonials/testimonial', array('store_id')),
        array('store_id'))
    ->addForeignKey($installer->getFkName('rg_testimonials/testimonial', 'customer_id', 'customer/entity', 'entity_id'),
        'customer_id', $installer->getTable('customer/entity'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_SET_NULL, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('rg_testimonials/testimonial', 'store_id', 'core/store', 'store_id'),
        'store_id', $installer->getTable('core/store'), 'store_id',
        Varien_Db_Ddl_Table::ACTION_SET_NULL, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Testimonials')
;
$installer->getConnection()->createTable($table);


$installer->endSetup();