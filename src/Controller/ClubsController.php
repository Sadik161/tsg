<?php

namespace App\Controller;

use App\Controller\AppController;

/**
 * Clubs Controller
 *
 * @property \App\Model\Table\ClubsTable $Clubs
 *
 * @method \App\Model\Entity\Club[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ClubsController extends AppController
{
	/**
	 * Index method
	 *
	 * @return \Cake\Http\Response|null
	 */
	public function index()
	{
		$this->paginate = [
			'contain' => ['Coaches', 'Teams'],
		];
		$clubs = $this->paginate($this->Clubs);

		$this->set(compact('clubs', 'teams'));
	}

	/**
	 * View method
	 *
	 * @param string|null $id Club id.
	 * @return \Cake\Http\Response|null
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function view($id = null)
	{
		$clubs = $this->Clubs->get($id, [
			'contain' => ['Coaches', 'Teams'],
		]);
		$clubs = $this->paginate($this->Clubs);
		$this->set('club', $clubs);
	}

	/**
	 * Add method
	 *
	 * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
	 */
	public function add()
	{
		$club = $this->Clubs->newEntity();
		if ($this->request->is('post')) {
			$club = $this->Clubs->patchEntity($club, $this->request->getData());
			if ($this->Clubs->save($club)) {
				$this->Flash->success(__('The club has been saved.'));

				return $this->redirect(['action' => 'index']);
			}
			$this->Flash->error(__('The club could not be saved. Please, try again.'));
		}
		$coaches = $this->Clubs->Coaches->find('list', ['limit' => 200]);
		$teams = $this->Clubs->Teams->find('list', ['limit' => 200]);
		$this->set(compact('club', 'coaches', 'teams'));
	}

	/**
	 * Edit method
	 *
	 * @param string|null $id Club id.
	 * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function edit($id = null)
	{
		$club = $this->Clubs->get($id, [
			'contain' => [],
		]);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$club = $this->Clubs->patchEntity($club, $this->request->getData());
			if ($this->Clubs->save($club)) {
				$this->Flash->success(__('The club has been saved.'));

				return $this->redirect(['action' => 'index']);
			}
			$this->Flash->error(__('The club could not be saved. Please, try again.'));
		}
		$coaches = $this->Clubs->Coaches->find('list', ['limit' => 200]);
		$teams = $this->Clubs->Teams->find('list', ['limit' => 200]);
		$this->set(compact('club', 'coaches', 'teams'));
	}

	/**
	 * Delete method
	 *
	 * @param string|null $id Club id.
	 * @return \Cake\Http\Response|null Redirects to index.
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function delete($id = null)
	{
		$this->request->allowMethod(['post', 'delete']);
		$club = $this->Clubs->get($id);
		if ($this->Clubs->delete($club)) {
			$this->Flash->success(__('The club has been deleted.'));
		} else {
			$this->Flash->error(__('The club could not be deleted. Please, try again.'));
		}

		return $this->redirect(['action' => 'index']);
	}
}
