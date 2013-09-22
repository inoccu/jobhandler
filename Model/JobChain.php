<?php
App::uses('AppModel', 'Model');
/**
 * JobChain Model
 *
 * @property JobChainOrder $JobChainOrder
 */
class JobChain extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'JobChainOrder' => array(
			'className' => 'JobChainOrder',
			'foreignKey' => 'job_chain_id',
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
