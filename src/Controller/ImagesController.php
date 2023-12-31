<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\EventInterface;

/**
 * Images Controller
 *
 * @property \App\Model\Table\ImagesTable $Images
 *
 * @method \App\Model\Entity\Image[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ImagesController extends AppController
{
	/**
	 * Index method
	 *
	 * @return \Cake\Http\Response|null
	 */
	public function index()
	{
		$images = $this->paginate($this->Images);

		$this->set(compact('images'));
	}

	/**
	 * View method
	 *
	 * @param string|null $id Image id.
	 * @return \Cake\Http\Response|null
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function view($id = null)
	{
		$image = $this->Images->get($id, [
			'contain' => [],
		]);

		$this->set('image', $image);
	}

	/**
	 * Add method
	 *
	 * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
	 */
	public function add()
	{
		$image = $this->Images->newEntity();
		if ($this->request->is('post')) {
			$image = $this->Images->patchEntity($image, $this->request->getData());
			if ($this->Images->save($image)) {
				$this->Flash->success(__('The image has been saved.'));

				return $this->redirect(['action' => 'index']);
			}
			$this->Flash->error(__('The image could not be saved. Please, try again.'));
		}
		$this->set(compact('image'));
	}

	/**
	 * Edit method
	 *
	 * @param string|null $id Image id.
	 * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function edit($id = null)
	{
		$image = $this->Images->get($id, [
			'contain' => [],
		]);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$image = $this->Images->patchEntity($image, $this->request->getData());
			if ($this->Images->save($image)) {
				$this->Flash->success(__('The image has been saved.'));

				return $this->redirect(['action' => 'index']);
			}
			$this->Flash->error(__('The image could not be saved. Please, try again.'));
		}
		$this->set(compact('image'));
	}

	/**
	 * Delete method
	 *
	 * @param string|null $id Image id.
	 * @return \Cake\Http\Response|null Redirects to index.
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function delete($id = null)
	{
		$this->request->allowMethod(['post', 'delete']);
		$image = $this->Images->get($id);
		if ($this->Images->delete($image)) {
			$this->Flash->success(__('The image has been deleted.'));
		} else {
			$this->Flash->error(__('The image could not be deleted. Please, try again.'));
		}

		return $this->redirect(['action' => 'index']);
	}

	public function upload()
	{
		$image = $this->Images->newEmptyEntity();

		if ($this->request->is('post')) {
			$file = $this->request->getData('image_file');


			$filename = uniqid() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
			$uploadPath = WWW_ROOT . 'img' . DS . 'uploads' . DS . $filename;

			if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
				$image->filename = $filename;
				$image->mimetype = $file['type'];

				if ($this->Images->save($image)) {
					$this->Flash->success(__('Bild erfolgreich hochgeladen und gespeichert.'));
				} else {
					$this->Flash->error(__('Das Bild konnte nicht gespeichert werden.'));
				}
			} else {
				$this->Flash->error(__('Beim Hochladen des Bildes ist ein Fehler aufgetreten.'));
			}
		}

		$this->set('image', $image);
	}
}
