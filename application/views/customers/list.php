<input type="hidden" id="permission" value="<?php echo $permission;?>">
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Clientes</h3>
          <?php
          if (strpos($permission,'Add') !== false) {
            echo '<button class="btn btn-block btn-success" style="width: 100px; margin-top: 10px;" data-toggle="modal" onclick="LoadCust(0,\'Add\')" id="btnAdd">Agregar</button>';
          }
          ?>
        </div><!-- /.box-header -->
        <div class="box-body">
          <table id="customers" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th width="20%">Acciones</th>
                <th>Cliente</th>
                <th>DNI - CUIT</th>
                <th>Domicilio</th>
                <th>Télefonos</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if($list) {  
              	foreach($list as $c)
    		        {
	                echo '<tr>';
	                echo '<td>';
                  if (strpos($permission,'Edit') !== false) {
	                	echo '<i class="fa fa-fw fa-pencil" style="color: #f39c12; cursor: pointer; margin-left: 15px;" onclick="LoadCust('.$c['cliId'].',\'Edit\')"></i>';
                  }
                  if (strpos($permission,'Del') !== false) {
	                	echo '<i class="fa fa-fw fa-times-circle" style="color: #dd4b39; cursor: pointer; margin-left: 15px;" onclick="LoadCust('.$c['cliId'].',\'Del\')"></i>';
                  }
                  if (strpos($permission,'View') !== false) {
	                	echo '<i class="fa fa-fw fa-search" style="color: #3c8dbc; cursor: pointer; margin-left: 15px;" onclick="LoadCust('.$c['cliId'].',\'View\')"></i>';
                  }
	                echo '</td>';
	                echo '<td style="text-align: left">'.$c['cliApellido'].' , '.$c['cliNombre'].'</td>';
                  echo '<td style="text-align: left"> ( '.$c['docDescripcion'].' ) '.$c['cliDocumento'].'</td>';
                  echo '<td style="text-align: left">'.$c['cliDomicilio'].'</td>';
                  echo '<td style="text-align: left">'.($c['cliTelefono'] != "" ? $c['cliTelefono'] : '').'</td>';
	                echo '</tr>';
                  
    		        }
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
    $('#customers').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true,
      "language": {
            "lengthMenu": "Ver _MENU_ filas por página",
            "zeroRecords": "No hay registros",
            "info": "Mostrando página _PAGE_ de _PAGES_",
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

  var idCli = 0;
  var acCli = '';
  
  function LoadCust(id_, action){
  	idCli = id_;
  	acCli = action;
  	LoadIconAction('modalAction',action);
  	WaitingOpen('Cargando Cliente');
      $.ajax({
          	type: 'POST',
          	data: { id : id_, act: action },
    		url: 'index.php/customer/getCustomer', 
    		success: function(result){
			                WaitingClose();
			                $("#modalBodyCustomer").html(result.html);
			                setTimeout("$('#modalCustomer').modal('show')",800);
    					},
    		error: function(result){
    					WaitingClose();
    					alert("error");
    				},
          	dataType: 'json'
    		});
  }

  
  $('#btnSave').click(function(){

  	if(acCli == 'View')
  	{
  		$('#modalCustomer').modal('hide');
  		return;
  	}

  	var hayError = false;
    if($('#cliId').val() == '')
    {
    	hayError = true;
    }

    if($('#cliNombre').val() == '')
    {
      hayError = true;
    }

    if($('#cliApellido').val() == '')
    {
      hayError = true;
    }

    if($('#cliDocumento').val() == '')
    {
      hayError = true;
    }

  
    if(hayError == true){
    	$('#errorCust').fadeIn('slow');
    	return;
    }

    $('#errorCust').fadeOut('slow');
    WaitingOpen('Guardando cambios');
    	$.ajax({
          	type: 'POST',
          	data: { 
                    id : idCli, 
                    act: acCli, 
                    nro: $('#cliId').val(),
                    name: $('#cliNombre').val(),
                    lnam: $('#cliApellido').val(),
                    doc: $('#docId').val(),
                    dni: $('#cliDocumento').val(),
                    mail: $('#cliMail').val(),
                    dom: $('#cliDomicilio').val(),
                    tel: $('#cliTelefono').val(),
                    est: $('#cliEstado').val()
                  },
    		url: 'index.php/customer/setCustomer', 
    		success: function(result){
                			WaitingClose();
                			$('#modalCustomer').modal('hide');
                			setTimeout("cargarView('customer', 'index', '"+$('#permission').val()+"');",1000);
    					},
    		error: function(result){
    					WaitingClose();
    					alert("error");
    				},
          	dataType: 'json'
    		});
  });

</script>

<style type="text/css">
    .contenedor{ width: 350px; float: center;}
    #camara, #foto, #imgCamera{
        width: 320px;
        min-height: 240px;
        border: 1px solid #008000;
    }
</style>

<!-- Modal -->
<div class="modal fade" id="modalCustomer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span id="modalAction"> </span> Cliente</h4> 
      </div>
      <div class="modal-body" id="modalBodyCustomer">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnSave">Guardar</button>
      </div>
    </div>
  </div>
</div>