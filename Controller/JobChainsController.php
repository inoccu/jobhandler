<?php
App::uses('AppController', 'Controller');
/**
 * JobChains Controller
 *
 * @property JobChain $JobChain
 * @property PaginatorComponent $Paginator
 */
class JobChainsController extends AppController {

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
		$this->JobChain->recursive = 0;
		$this->set('jobChains', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->JobChain->exists($id)) {
			throw new NotFoundException(__('Invalid job chain'));
		}
		$options = array('conditions' => array('JobChain.' . $this->JobChain->primaryKey => $id));
		$this->set('jobChain', $this->JobChain->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->JobChain->create();
			if ($this->JobChain->save($this->request->data)) {
				$this->Session->setFlash(__('The job chain has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The job chain could not be saved. Please, try again.'));
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
		if (!$this->JobChain->exists($id)) {
			throw new NotFoundException(__('Invalid job chain'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->JobChain->save($this->request->data)) {
				$this->Session->setFlash(__('The job chain has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The job chain could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('JobChain.' . $this->JobChain->primaryKey => $id));
			$this->request->data = $this->JobChain->find('first', $options);
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
		$this->JobChain->id = $id;
		if (!$this->JobChain->exists()) {
			throw new NotFoundException(__('Invalid job chain'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->JobChain->delete()) {
			$this->Session->setFlash(__('The job chain has been deleted.'));
		} else {
			$this->Session->setFlash(__('The job chain could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
