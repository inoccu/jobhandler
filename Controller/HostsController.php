<?php
App::uses('AppController', 'Controller');
/**
 * Hosts Controller
 *
 * @property Host $Host
 * @property PaginatorComponent $Paginator
 */
class HostsController extends AppController {

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
		$this->Host->recursive = 0;
		$this->set('hosts', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Host->exists($id)) {
			throw new NotFoundException(__('Invalid host'));
		}
		$options = array('conditions' => array('Host.' . $this->Host->primaryKey => $id));
		$this->set('host', $this->Host->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Host->create();
			if ($this->Host->save($this->request->data)) {
				$this->Session->setFlash(__('The host has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The host could not be saved. Please, try again.'));
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
		if (!$this->Host->exists($id)) {
			throw new NotFoundException(__('Invalid host'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Host->save($this->request->data)) {
				$this->Session->setFlash(__('The host has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The host could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Host.' . $this->Host->primaryKey => $id));
			$this->request->data = $this->Host->find('first', $options);
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
		$this->Host->id = $id;
		if (!$this->Host->exists()) {
			throw new NotFoundException(__('Invalid host'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Host->delete()) {
			$this->Session->setFlash(__('The host has been deleted.'));
		} else {
			$this->Session->setFlash(__('The host could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
