<?php
// O Código está seguindo as regras da PSR como: Chave abaixo para funções e classes, nome de funções com a primeira letra a partir da segunda paravra em maiúsculo, porém os verbos estão separados com _ por restrição da API Rest empregada no sistema.

//Chamando a biblioteca REST_Controller para trabalhar com os padrões de WebService, Verbos HTTP e respostas em jSON.
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Controller_incident extends REST_Controller {

    //Construtor chamando a model que será usada
    function __construct() {
        parent::__construct();
        $this->load->model('model_incident');
    }

    /*
        Função List, verbo GET. Responsável por buscar e listar todos incidentes registrados
        Não recebe parametro!
    */
    public function list_get()
    {
        $incidents = $this->model_incident->get();
        $data = array(
            'result' => 'Lista de incidentes com '.count($incidents).' registros!',
            'incidents' => $incidents
        );
        $this->response($data, Self::HTTP_OK);
    }

    /*
        Função New, verbo POST. Responsável por registrar um incidente,
        @param user, tipo int que recebe o ID do Atendente do incidente OBRIGATÓRIO,
        @param client, tipo int que recebe o ID do Cliente do incidente OBRIGATÓRIO,
        @param description, tipo string que recebe a descricao do incidente OBRIGATÓRIO.
    */
    public function new_post()
    {

        //Array com os dados a serem inseridos.
        $data = array(
            'atendente' => $this->post('user'),
            'cliente'   => $this->post('client'),
            'descricao' => $this->post('description')
        );

        //Usando a validação de formulário para garantir integridade
        $this->form_validation->set_data($data);
        $this->form_validation->set_rules('atendente',  'Atendente','required|integer|callback_atendente');
        $this->form_validation->set_rules('cliente',    'Cliente',  'required|integer|callback_cliente');
        $this->form_validation->set_rules('descricao',  'Descrição','required');

        if ($this->form_validation->run()) {
        
            $this->model_incident->start();
            $id = $this->model_incident->create($data);
            $this->model_incident->commit();

            $this->response(
                array('result' => "Incidente registrado com sucesso!", 'id' => $id),
                Self::HTTP_OK); //HTTP Status 200

        } else { //Caso exista erro no cadastro.

            //Recuperando o erro tratado pelo framework e tirando tags HTML.
            $errors = strip_tags(validation_errors());
            $this->response(
                array('result' => $errors),
                Self::HTTP_BAD_REQUEST); //HTTP Status 400

        }  

    }

    /*
        Função End, verbo PUT. Responsável por alterar o status de um incidente dando por encerrado.
        @param id, tipo int que recebe o ID do incidente a ser editado OBRIGATÓRIO.
    */
    public function end_put()
    {
        $id = array('id' => $this->put('id'));

        //Usando a validação de formulário para garantir integridade
        $this->form_validation->set_data($id);
        $this->form_validation->set_rules('id',  'ID Incidente','required|integer|callback_id');

        if ($this->form_validation->run()) {
        
            $this->model_incident->start();
            $this->model_incident->update($id['id']);
            $this->model_incident->commit();

            $this->response(
                array('result' => "Incidente encerrado com sucesso!"),
                Self::HTTP_OK); //HTTP Status 200

        } else { //Caso exista erro no cadastro.

            //Recuperando o erro tratado pelo framework e tirando tags HTML.
            $errors = strip_tags(validation_errors());
            $this->response(
                array('result' => $errors),
                Self::HTTP_BAD_REQUEST); //HTTP Status 400

        }  

    }

    //Função responsável por validar o ID do Atendente e responder CALLBACK para validar o insert
    public function atendente($user){

        if ($this->model_incident->exists($user,"atendentes")) {
            return TRUE;
        } else {
            $this->form_validation->set_message('atendente', "Atendente não localizado");
            return FALSE;
        }

    }

    //Função responsável por validar o ID do Cliente e responder CALLBACK para validar o insert
    public function cliente($client){

        if ($this->model_incident->exists($client,"clientes")) {
            return TRUE;
        } else {
            $this->form_validation->set_message('cliente', "Cliente não localizado");
            return FALSE;
        }

    }

    //Função responsável por validar o ID do Cliente e responder CALLBACK para validar o insert
    public function id($incident){

        if ($this->model_incident->exists($incident,"incidentes")) {
            return TRUE;
        } else {
            $this->form_validation->set_message('id', "Incidente não localizado");
            return FALSE;
        }

    }
    
}
