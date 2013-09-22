<?php
App::uses('AppController', 'Controller');
/**
 * SchedulerOrderHistories Controller
 *
 * @property SchedulerOrderHistory $SchedulerOrderHistory
 * @property PaginatorComponent $Paginator
 */
class SchedulerOrderHistoriesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->SchedulerOrderHistory->recursive = 0;
		$this->set('schedulerOrderHistories', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->SchedulerOrderHistory->exists($id)) {
			throw new NotFoundException(__('Invalid scheduler order history'));
		}
		$options = array('conditions' => array('SchedulerOrderHistory.' . $this->SchedulerOrderHistory->primaryKey => $id));
		$this->set('schedulerOrderHistory', $this->SchedulerOrderHistory->find('first', $options));
	}

}
