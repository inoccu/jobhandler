<?php
App::uses('JobChainOrder', 'Model');

/**
 * JobChainOrder Test Case
 *
 */
class JobChainOrderTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.job_chain_order',
		'app.host',
		'app.job_chain',
		'app.scheduler_order',
		'app.scheduler_order_history'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->JobChainOrder = ClassRegistry::init('JobChainOrder');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->JobChainOrder);

		parent::tearDown();
	}

}
