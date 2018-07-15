<?php 

    //As funções abaixo deixei como QUERYs para que meu código SQL seja avaliado como pedido, mas normalmente uso as funções do Framework, ->insert, ->update e ->get.

    class Model_incident extends CI_Model {

        //Inicia a transaction.
        public function start(){
            $this->db->trans_begin();
        }
        
        //Se não houverem erros envia o commit
        public function commit(){

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }

            return array('status' => $this->db->trans_status());
        }
        
        //Caso o erro seja detectado de outra forma é possível forçar um rollback
        public function rollback(){
            $this->db->trans_rollback();
        }

        /*
            Função que recupera a lista de incidentes
            São exibidos os dados das 3 tabelas, sendo que a ordem vem primeiro ordenado por Status: Aberto / Encerrados, e seguindo por data da mais recente para mais antiga, assim os incidentes devem vir no início da lista os últimos abertos.

        */
        public function get(){
            return $this->db->query("SELECT 
                        i.id as incidente,
                        a.nome as atendente,
                        c.nome as cliente,
                        c.empresa,
                        date_format(i.creation_time, '%d/%m/%Y às %H:%i:%s') as data_registro,
                        i.descricao,
                        i.status
                            FROM incidentes i
                                inner join atendentes a on atendente = a.id
                                inner join clientes c on cliente = c.id
                                    order by status asc, creation_time desc")->result();
        }

        /*
            Função que atualiza o status de um incidente
        */
        public function update($id){
            
            return $this->db->query("UPDATE incidentes 
                                        set status = 'encerrado' 
                                            where id = {$id}");

        }

        /*
            Função que registra um incidente
        */
        public function create($data){

            $this->db->query("INSERT into incidentes (atendente,cliente,descricao,status,creation_time) 
                                values ({$data['atendente']},{$data['cliente']},'{$data['descricao']}','aberto',CURRENT_TIMESTAMP)");
            return $this->db->insert_id();

        }

        /*
            Função que valida de forma genérica
        */
        public function exists($id,$table){

            $this->db->where("id",$id);
            $query = $this->db->get($table);
            if ($query->num_rows() > 0){
                return true;
            } else {
                return false;
            }

        }

        /*
            Função que lista todos Atendentes
        */
        public function getUsers(){
            return $this->db->query("select id, nome from atendentes")->result();
        }

        /*
            Função que lista todos clientes
        */
        public function getClients(){
            return $this->db->query("select id, nome from clientes")->result();  
        }

    }