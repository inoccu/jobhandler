<?php
App::uses('AppModel', 'Model');
/**
 * JobChainOrder Model
 *
 * @property Host $Host
 * @property JobChain $JobChain
 * @property SchedulerOrder $SchedulerOrder
 * @property SchedulerOrderHistory $SchedulerOrderHistories
 */
class JobChainOrder extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'nagios_service_description';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Host' => array(
			'className' => 'Host',
			'foreignKey' => 'host_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'JobChain' => array(
			'className' => 'JobChain',
			'foreignKey' => 'job_chain_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'SchedulerOrder' => array(
			'className' => 'SchedulerOrder',
			'foreignKey' => 'order_id',
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
