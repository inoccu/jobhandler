<?php
/**
 * JobChainOrderFixture
 *
 */
class JobChainOrderFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'host_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'job_chain_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'order_id' => array('type' => 'integer', 'null' => true, 'default' => null),
		'nagios_service_description' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'param_1' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'param_2' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'param_3' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'param_4' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'param_5' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'host_id' => 1,
			'job_chain_id' => 1,
			'order_id' => 1,
			'nagios_service_description' => 'Lorem ipsum dolor sit amet',
			'param_1' => 'Lorem ipsum dolor sit amet',
			'param_2' => 'Lorem ipsum dolor sit amet',
			'param_3' => 'Lorem ipsum dolor sit amet',
			'param_4' => 'Lorem ipsum dolor sit amet',
			'param_5' => 'Lorem ipsum dolor sit amet',
			'created' => '2013-09-22 18:04:48',
			'modified' => '2013-09-22 18:04:48'
		),
	);

}
