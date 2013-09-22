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

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->SchedulerOrderHistory->create();
			if ($this->SchedulerOrderHistory->save($this->request->data)) {
				$this->Session->setFlash(__('The scheduler order history has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The scheduler order history could not be saved. Please, try again.'));
			}
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->SchedulerOrderHistory->exists($id)) {
			throw new NotFoundException(__('Invalid scheduler order history'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->SchedulerOrderHistory->save($this->request->data)) {
				$this->Session->setFlash(__('The scheduler order history has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The scheduler order history could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('SchedulerOrderHistory.' . $this->SchedulerOrderHistory->primaryKey => $id));
			$this->request->data = $this->SchedulerOrderHistory->find('first', $options);
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->SchedulerOrderHistory->id = $id;
		if (!$this->SchedulerOrderHistory->exists()) {
			throw new NotFoundException(__('Invalid scheduler order history'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->SchedulerOrderHistory->delete()) {
			$this->Session->setFlash(__('The scheduler order history has been deleted.'));
		} else {
			$this->Session->setFlash(__('The scheduler order history could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
