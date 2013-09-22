<?php
App::uses('JobChain', 'Model');

/**
 * JobChain Test Case
 *
 */
class JobChainTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.job_chain',
		'app.job_chain_order',
		'app.host'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->JobChain = ClassRegistry::init('JobChain');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->JobChain);

		parent::tearDown();
	}

}
