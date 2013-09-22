<?php
App::uses('SchedulerOrder', 'Model');

/**
 * SchedulerOrder Test Case
 *
 */
class SchedulerOrderTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.scheduler_order',
		'app.job_chain_order',
		'app.host',
		'app.job_chain',
		'app.scheduler_order_history'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->SchedulerOrder = ClassRegistry::init('SchedulerOrder');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SchedulerOrder);

		parent::tearDown();
	}

}
