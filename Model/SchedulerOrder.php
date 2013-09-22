<?php
App::uses('AppModel', 'Model');
/**
 * SchedulerOrder Model
 *
 * @property JobChainOrder $JobChainOrder
 * @property SchedulerOrderHistory $SchedulerOrderHistories
 */
class SchedulerOrder extends AppModel {

/**
 * Use database config
 *
 * @var string
 */
	public $useDbConfig = 'scheduler';

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'title';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'JobChainOrder' => array(
			'className' => 'JobChainOrder',
			'foreignKey' => 'id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'SchedulerOrderHistories' => array(
			'className' => 'SchedulerOrderHistory',
			'foreignKey' => 'order_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

}
