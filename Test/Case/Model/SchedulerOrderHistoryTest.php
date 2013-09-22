<?php
App::uses('SchedulerOrderHistory', 'Model');

/**
 * SchedulerOrderHistory Test Case
 *
 */
class SchedulerOrderHistoryTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.scheduler_order_history'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->SchedulerOrderHistory = ClassRegistry::init('SchedulerOrderHistory');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SchedulerOrderHistory);

		parent::tearDown();
	}

}
