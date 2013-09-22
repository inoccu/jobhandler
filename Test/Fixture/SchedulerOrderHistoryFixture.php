<?php
/**
 * SchedulerOrderHistoryFixture
 *
 */
class SchedulerOrderHistoryFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 'scheduler_order_history';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'history_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 9, 'key' => 'primary'),
		'job_chain' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'order_id' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'spooler_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'key' => 'index', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'title' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 200, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'state' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'state_text' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'start_time' => array('type' => 'datetime', 'null' => false, 'default' => null, 'key' => 'index'),
		'end_time' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'log' => array('type' => 'binary', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'history_id', 'unique' => 1),
			'scheduler_o_history_spooler_id' => array('column' => 'spooler_id', 'unique' => 0),
			'scheduler_o_history_job_chain' => array('column' => array('job_chain', 'order_id'), 'unique' => 0),
			'scheduler_o_history_start_time' => array('column' => 'start_time', 'unique' => 0)
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
			'history_id' => 1,
			'job_chain' => 'Lorem ipsum dolor sit amet',
			'order_id' => 'Lorem ipsum dolor sit amet',
			'spooler_id' => 'Lorem ipsum dolor sit amet',
			'title' => 'Lorem ipsum dolor sit amet',
			'state' => 'Lorem ipsum dolor sit amet',
			'state_text' => 'Lorem ipsum dolor sit amet',
			'start_time' => '2013-09-22 17:17:03',
			'end_time' => '2013-09-22 17:17:03',
			'log' => 'Lorem ipsum dolor sit amet'
		),
	);

}
