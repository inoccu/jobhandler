<?php
App::uses('AppController', 'Controller');
/**
 * JobChainOrders Controller
 *
 * @property JobChainOrder $JobChainOrder
 * @property PaginatorComponent $Paginator
 */
class JobChainOrdersController extends AppController {

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
		$this->JobChainOrder->recursive = 0;
		$this->set('jobChainOrders', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->JobChainOrder->exists($id)) {
			throw new NotFoundException(__('Invalid job chain order'));
		}
		$options = array('conditions' => array('JobChainOrder.' . $this->JobChainOrder->primaryKey => $id));
		$this->set('jobChainOrder', $this->JobChainOrder->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->JobChainOrder->create();
			if ($this->JobChainOrder->save($this->request->data)) {
				$this->Session->setFlash(__('The job chain order has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The job chain order could not be saved. Please, try again.'));
			}
		}

		if (array_key_exists('host_id', $this->passedArgs)) {
			$hosts = $this->JobChainOrder->Host->find('list', array('conditions' => array('id' => $this->passedArgs['host_id'])));
		} else {
			$hosts = $this->JobChainOrder->Host->find('list');
		}
		$jobChains = $this->JobChainOrder->JobChain->find('list');
		$schedulerOrders = $this->JobChainOrder->SchedulerOrder->find('list');
		$this->set(compact('hosts', 'jobChains', 'schedulerOrders'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->JobChainOrder->exists($id)) {
			throw new NotFoundException(__('Invalid job chain order'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->JobChainOrder->save($this->request->data)) {
				$this->Session->setFlash(__('The job chain order has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The job chain order could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('JobChainOrder.' . $this->JobChainOrder->primaryKey => $id));
			$this->request->data = $this->JobChainOrder->find('first', $options);
		}
		$hosts = $this->JobChainOrder->Host->find('list');
		$jobChains = $this->JobChainOrder->JobChain->find('list');
		$schedulerOrders = $this->JobChainOrder->SchedulerOrder->find('list');
		$this->set(compact('hosts', 'jobChains', 'schedulerOrders'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->JobChainOrder->id = $id;
		if (!$this->JobChainOrder->exists()) {
			throw new NotFoundException(__('Invalid job chain order'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->JobChainOrder->delete()) {
			$this->Session->setFlash(__('The job chain order has been deleted.'));
		} else {
			$this->Session->setFlash(__('The job chain order could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
