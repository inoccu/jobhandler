<?php
App::uses('Host', 'Model');

/**
 * Host Test Case
 *
 */
class HostTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.host',
		'app.job_chain_order'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Host = ClassRegistry::init('Host');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Host);

		parent::tearDown();
	}

}
