<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Controller_web extends CI_Controller {

    function __construct() 
    {
        parent::__construct();
        $this->load->model('model_incident');
    }

	public function index()
	{
        //traz todos incidentes inicialmente, também todos Usuários e Clientes
        $incidents['incidents'] = $this->model_incident->get();
        $incidents['users'] = $this->model_incident->getUsers();
        $incidents['clients'] = $this->model_incident->getClients();

		$this->load->view('index',$incidents);
	}

    public function updateAjax(){

        $incidents = $this->model_incident->get();

        echo '<table class="table table-hover" id="table">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Atendente</th>
              <th scope="col">Cliente</th>
              <th scope="col">Empresa</th>
              <th scope="col">Registrado Em:</th>
              <th scope="col">Descrição</th>
              <th scope="col">Status</th>
              <th scope="col">Encerrar</th>
            </tr>
          </thead>
          <tbody>';
                foreach ($incidents as $key => $incident) {
                    echo "<tr>";
                    echo "<td>{$incident->incidente}</td>";
                    echo "<td>{$incident->atendente}</td>";
                    echo "<td>{$incident->cliente}</td>";
                    echo "<td>{$incident->empresa}</td>";
                    echo "<td>{$incident->data_registro}</td>";
                    echo "<td>{$incident->descricao}</td>";
                    echo "<td>{$incident->status}</td>";
                    echo '<td><button class="btn btn-success end" cod="'.$incident->incidente.'">Encerrar</button></td>';
                    echo "</tr>";
                }
          echo '</tbody>
        </table>';

    }
	
}
