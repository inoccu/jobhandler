<?php
App::uses('AppModel', 'Model');
/**
 * SchedulerOrderHistory Model
 *
 */
class SchedulerOrderHistory extends AppModel {

/**
 * Use database config
 *
 * @var string
 */
	public $useDbConfig = 'scheduler';

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'scheduler_order_history';

/**
 * Primary key field
 *
 * @var string
 */
	public $primaryKey = 'history_id';

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'title';

}
