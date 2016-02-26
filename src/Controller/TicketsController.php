<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Tickets Controller
 *
 * @property \App\Model\Table\TicketsTable $Tickets
 */
class TicketsController extends AppController
{

public function isAuthorized($user) {
		$action = $this->request->params ['action'];
	
		// Allow all users to logout and see dashboard
		if (in_array ( $action, ['index'] ) && ! empty ( $user )) {
			return true;
		}
		
		if (in_array ( $action, [
				'view',
				'edit',
				'delete'
		] ) && $this->Tickets->exists(
				['user_id' => $user['id'], 
				 'id' => $this->request->params ['pass'] [0]
				] ) ) {
			return true;
		}
	
		return parent::isAuthorized ( $user );
	}
    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Attachments', 'Departments', 'TicketTypes', 'TicketStatus', 'Users', 'Projects']
        ];
        $this->set('tickets', $this->paginate($this->Tickets));
        $this->set('_serialize', ['tickets']);
    }

    /**
     * View method
     *
     * @param string|null $id Ticket id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ticket = $this->Tickets->get($id, [
            'contain' => ['Departments', 'Types', 'TicketStatus', 'Users', 'Projects', 'Tasks', 'Comments']
        ]);
        $this->set('ticket', $ticket);
        $this->set('_serialize', ['ticket']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $ticket = $this->Tickets->newEntity();
        if ($this->request->is('post')) {
            $ticket = $this->Tickets->patchEntity($ticket, $this->request->data);
            if ($this->Tickets->save($ticket)) {
                $this->Flash->success(__('The ticket has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The ticket could not be saved. Please, try again.'));
            }
        }
        $departments = $this->Tickets->Departments->find('list', ['limit' => 200]);
        $types = $this->Tickets->Types->find('list', ['limit' => 200]);
        $status = $this->Tickets->TicketStatus->find('list', ['limit' => 200]);
        $users = $this->Tickets->Users->find('list', ['limit' => 200]);
        $projects = $this->Tickets->Projects->find('list', ['limit' => 200]);
        $this->set(compact('ticket', 'departments', 'types', 'TicketStatus', 'users', 'projects'));
        $this->set('_serialize', ['ticket']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Ticket id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ticket = $this->Tickets->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $ticket = $this->Tickets->patchEntity($ticket, $this->request->data);
            if ($this->Tickets->save($ticket)) {
                $this->Flash->success(__('The ticket has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The ticket could not be saved. Please, try again.'));
            }
        }
        $departments = $this->Tickets->Departments->find('list', ['limit' => 200]);
        $types = $this->Tickets->Types->find('list', ['limit' => 200]);
        $status = $this->Tickets->TicketStatus->find('list', ['limit' => 200]);
        $users = $this->Tickets->Users->find('list', ['limit' => 200]);
        $projects = $this->Tickets->Projects->find('list', ['limit' => 200]);
        $this->set(compact('ticket', 'departments', 'types', 'ticketstatus', 'users', 'projects'));
        $this->set('_serialize', ['ticket']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Ticket id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ticket = $this->Tickets->get($id);
        if ($this->Tickets->delete($ticket)) {
            $this->Flash->success(__('The ticket has been deleted.'));
        } else {
            $this->Flash->error(__('The ticket could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
