<?php

namespace App\Controller;

use App\Controller\AppController;

/**
 * Teams Controller
 *
 * @property \App\Model\Table\TeamsTable $Teams
 *
 * @method \App\Model\Entity\Team[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TeamsController extends AppController
{
	/**
	 * Index method
	 *
	 * @return \Cake\Http\Response|null
	 */
	public function index()
	{
		$this->paginate = [
			'contain' => ['Coaches', 'clubs'],
		];
		$teams = $this->paginate($this->Teams);

		$this->set(compact('teams'));
	}

	/**
	 * View method
	 *
	 * @param string|null $id Team id.
	 * @return \Cake\Http\Response|null
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function view($id = null)
	{
		$team = $this->Teams->get($id, [
			'contain' => ['Coaches', 'Players'],
		]);
		$this->set('team', $team);
	}

	/**
	 * Add method
	 *
	 * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
	 */
	public function add()
	{
		$team = $this->Teams->newEntity();
		if ($this->request->is('post')) {
			$team = $this->Teams->patchEntity($team, $this->request->getData());
			if ($this->Teams->save($team)) {
				$this->Flash->success(__('The team has been saved.'));

				return $this->redirect(['action' => 'index']);
			}
			$this->Flash->error(__('The team could not be saved. Please, try again.'));
		}
		$coaches = $this->Teams->Coaches->find('list', ['limit' => 200]);
		$clubs = $this->Teams->Clubs->find('list', ['limit' => 200]);
		$this->set(compact('team', 'coaches', 'clubs'));
	}

	/**
	 * Edit method
	 *
	 * @param string|null $id Team id.
	 * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function edit($id = null)
	{
		$team = $this->Teams->get($id, [
			'contain' => [],
		]);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$team = $this->Teams->patchEntity($team, $this->request->getData());
			if ($this->Teams->save($team)) {
				$this->Flash->success(__('The team has been saved.'));

				return $this->redirect(['action' => 'index']);
			}
			$this->Flash->error(__('The team could not be saved. Please, try again.'));
		}
		$this->set(compact('team'));
	}

	/**
	 * Delete method
	 *
	 * @param string|null $id Team id.
	 * @return \Cake\Http\Response|null Redirects to index.
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function delete($id = null)
	{
		$this->request->allowMethod(['post', 'delete']);
		$team = $this->Teams->get($id);
		if ($this->Teams->delete($team)) {
			$this->Flash->success(__('The team has been deleted.'));
		} else {
			$this->Flash->error(__('The team could not be deleted. Please, try again.'));
		}

		return $this->redirect(['action' => 'index']);
	}

	public function isAuthorized($user)
	{
		if ($user['role'] === 'admin') {
			return true;
		}
		if ($user['role'] === 'user') {
			return false;
		}
	}
}
