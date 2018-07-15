<!DOCTYPE html>
<html>
	<head>
		<title>Incidentes Online</title>

		<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>

		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>

		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
  
		<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>

		<style type="text/css">
			body {
				padding: 50px;
			}
		</style>

	</head>

	<body>

		<div class="row">
			<div class="col-8">
				<h1>Incidentes</h1>
			</div>
			<div class="col-4" align="right">
				<button class="btn btn-success" data-toggle="modal" data-target="#addModal">		Adicionar
				</button>
				<button class="btn btn-info" id="more">Recarregar</button>
			</div>
		</div>

		<hr>

		<div class="row">
			<div class="col" id="load">
				<table class="table table-hover" id="table">
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
				  <tbody>
				    <?php 
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
				    ?>
				  </tbody>
				</table>
			</div>
		</div>

	</body>
</html>

<!-- Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addModalLabel">Novo Incidente</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
        	<div class="col">
        		<label for="user">Atendente</label>
        		<select class="form-control" id="user">
        			<?php 
        				foreach ($users as $key => $user) {
        					echo '<option value="'.$user->id.'">'.$user->nome.'</option>';
        				}
        			?>
        		</select>
        	</div>
        	<div class="col">
        		<label for="client">Cliente</label>
        		<select class="form-control" id="client">
        			<?php 
        				foreach ($clients as $key => $client) {
        					echo '<option value="'.$client->id.'">'.$client->nome.'</option>';
        				}
        			?>
        		</select>
        	</div>
        </div>
        <div class="row">
        	<div class="col">
        		<label for="description">Descrição</label>
        		<textarea type="text" class="form-control" name="description" id="description"></textarea>
        	</div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-success" id="add">Enviar</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		//Cria o filtro / paginação da tabela
		$('#table').DataTable({"ordering": false});

		//Adicionar um novo incidente a lista.
		$("#add").click(function(){

			if($("#description").val() == ""){
				$("#description").val("Sem Descrição!");
			}

			var settings = {
			  "url": "incident",
			  "method": "POST",
			  "headers": {
			    "Content-Type": "application/x-www-form-urlencoded",
			  },
			  "data": {
			    "client": 		$("#client").val(),
			    "user": 		$("#user").val(),
			    "description": 	$("#description").val()
			  }
			}

			$.ajax(settings).done(function (response) {
			  updateList();
			  $('#addModal').modal('hide')
			});

		});

		//Atualiza a lista.
		$("#more").click(function(){
			updateList();
		});

		//usa o AJAX para encerrar um incidente
		$(document).on("click",".end",function(){
			var settings = {
			  "url": "incident",
			  "method": "PUT",
			  "headers": {
			    "Content-Type": "application/x-www-form-urlencoded"
			  },
			  "data": {
			    "id": $(this).attr('cod') //Pegando o atributo código que recebe o ID
			  }
			}

			$.ajax(settings).done(function (response) {
			  updateList();
			}).fail(function (){
				alert("Infelizmente esse servidor não responde funções PUT/DELETE.");
			});
			
		});

		//Atualiza a lista a cada insert, pedido de recarregamento e encerramento.
		function updateList(){
			$("#load").load("controller_web/updateAjax",function(){
				//Adicionando novamente o datatable
				$('#table').DataTable({"ordering": false});
			});
		}

	})
</script>