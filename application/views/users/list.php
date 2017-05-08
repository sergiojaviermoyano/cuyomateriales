<input type="hidden" id="permission" value="<?php echo $permission;?>">
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Usuarios</h3>
          <?php
          if (strpos($permission,'Add') !== false) {
            echo '<button class="btn btn-block btn-success" style="width: 100px; margin-top: 10px;" data-toggle="modal" onclick="LoadUsr(0,\'Add\')" id="btnAdd">Agregar</button>';
          }
          ?>
        </div><!-- /.box-header -->
        <div class="box-body">
          <table id="users" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th width="20%">Acciones</th>
                <th>Usuario</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Comisión</th>
              </tr>
            </thead>
            <tbody>
              <?php
              	foreach($list as $u)
    		        {
                  //var_dump($u);
                  
	                echo '<tr>';
	                echo '<td>';
                  if (strpos($permission,'Add') !== false) {
	                	echo '<i class="fa fa-fw fa-pencil" style="color: #f39c12; cursor: pointer; margin-left: 15px;" onclick="LoadUsr('.$u['usrId'].',\'Edit\')"></i>';
                  }
                  if (strpos($permission,'Del') !== false) {
	                	echo '<i class="fa fa-fw fa-times-circle" style="color: #dd4b39; cursor: pointer; margin-left: 15px;" onclick="LoadUsr('.$u['usrId'].',\'Del\')"></i>';
                  }
                  if (strpos($permission,'View') !== false) {
	                	echo '<i class="fa fa-fw fa-search" style="color: #3c8dbc; cursor: pointer; margin-left: 15px;" onclick="LoadUsr('.$u['usrId'].',\'View\')"></i>';
                  }
	                echo '</td>';
	                echo '<td style="text-align: left">'.$u['usrNick'].'</td>';
                  echo '<td style="text-align: left">'.$u['usrName'].'</td>';
                  echo '<td style="text-align: left">'.$u['usrLastName'].'</td>';
                  echo '<td style="text-align: right">'.$u['usrComision'].' %</td>';
	                echo '</tr>';
                  
    		        }
              ?>
            </tbody>
          </table>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div><!-- /.col -->
  </div><!-- /.row -->
</section><!-- /.content -->

<script>
  $(function () {
    //$("#groups").DataTable();
    $('#users').DataTable({
            "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true,
      "language": {
            "lengthMenu": "Ver _MENU_ filas por página",
            "zeroRecords": "No hay registros",
            "info": "Mostrando pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay registros disponibles",
            "infoFiltered": "(filtrando de un total de _MAX_ registros)",
            "sSearch": "Buscar:  ",
            "oPaginate": {
                "sNext": "Sig.",
                "sPrevious": "Ant."
              }
        }
    });
  });

  
  var idUsr = 0;
  var acUsr = '';
  
  function LoadUsr(id_, action){
  	idUsr = id_;
  	acUsr = action;
  	LoadIconAction('modalAction',action);
  	WaitingOpen('Cargando Usuario');
      $.ajax({
          	type: 'POST',
          	data: { id : id_, act: action },
    		url: 'index.php/user/getUser', 
    		success: function(result){
			                WaitingClose();
			                $("#modalBodyUsr").html(result.html);
			                setTimeout("$('#modalUsr').modal('show')",800);
    					},
    		error: function(result){
    					WaitingClose();
    					alert("error");
    				},
          	dataType: 'json'
    		});
  }

  
  $('#btnSave').click(function(){
  	
  	if(acUsr == 'View')
  	{
  		$('#modalUsr').modal('hide');
  		return;
  	}

  	var hayError = false;
    if($('#usrNick').val() == '')
    {
    	hayError = true;
    }

    if($('#usrName').val() == '')
    {
      hayError = true;
    }

    if($('#usrLastName').val() == '')
    {
      hayError = true;
    }

    if($('#usrComision').val() == '')
    {
      hayError = true;
    }

    if($('#usrPassword').val() != $('#usrPasswordConf').val()){
      hayError = true;
    }

    if(hayError == true){
    	$('#errorUsr').fadeIn('slow');
    	return;
    }

    $('#errorUsr').fadeOut('slow');
    WaitingOpen('Guardando cambios');
    	$.ajax({
          	type: 'POST',
          	data: { 
                    id : idUsr, 
                    act: acUsr, 
                    usr: $('#usrNick').val(),
                    name: $('#usrName').val(),
                    lnam: $('#usrLastName').val(),
                    com: $('#usrComision').val(),
                    pas: $('#usrPassword').val(),
                    grp: $('#grpId').val()
                  },
    		url: 'index.php/user/setUser', 
    		success: function(result){
                			WaitingClose();
                			$('#modalUsr').modal('hide');
                			setTimeout("cargarView('user', 'index', '"+$('#permission').val()+"');",1000);
    					},
    		error: function(result){
    					WaitingClose();
    					alert("error");
    				},
          	dataType: 'json'
    		});
  });

</script>


<!-- Modal -->
<div class="modal fade" id="modalUsr" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span id="modalAction"> </span> Usuario</h4> 
      </div>
      <div class="modal-body" id="modalBodyUsr">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnSave">Guardar</button>
      </div>
    </div>
  </div>
</div>