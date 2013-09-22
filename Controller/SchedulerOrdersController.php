<?php
App::uses('AppController', 'Controller');
/**
 * SchedulerOrders Controller
 *
 * @property SchedulerOrder $SchedulerOrder
 * @property PaginatorComponent $Paginator
 */
class SchedulerOrdersController extends AppController {

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
		$this->SchedulerOrder->recursive = 0;
		$this->set('schedulerOrders', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->SchedulerOrder->exists($id)) {
			throw new NotFoundException(__('Invalid scheduler order'));
		}
		$options = array('conditions' => array('SchedulerOrder.' . $this->SchedulerOrder->primaryKey => $id));
		$this->set('schedulerOrder', $this->SchedulerOrder->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->SchedulerOrder->create();
			if ($this->SchedulerOrder->save($this->request->data)) {
				$this->Session->setFlash(__('The scheduler order has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The scheduler order could not be saved. Please, try again.'));
			}
		}
		$jobChainOrders = $this->SchedulerOrder->JobChainOrder->find('list');
		$this->set(compact('jobChainOrders'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->SchedulerOrder->exists($id)) {
			throw new NotFoundException(__('Invalid scheduler order'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->SchedulerOrder->save($this->request->data)) {
				$this->Session->setFlash(__('The scheduler order has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The scheduler order could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('SchedulerOrder.' . $this->SchedulerOrder->primaryKey => $id));
			$this->request->data = $this->SchedulerOrder->find('first', $options);
		}
		$jobChainOrders = $this->SchedulerOrder->JobChainOrder->find('list');
		$this->set(compact('jobChainOrders'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->SchedulerOrder->id = $id;
		if (!$this->SchedulerOrder->exists()) {
			throw new NotFoundException(__('Invalid scheduler order'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->SchedulerOrder->delete()) {
			$this->Session->setFlash(__('The scheduler order has been deleted.'));
		} else {
			$this->Session->setFlash(__('The scheduler order could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
