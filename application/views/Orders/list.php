<input type="hidden" id="permission" value="<?php echo $permission;?>">
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Ordenes de Compra</h3>
          <?php
          if (strpos($permission,'Add') !== false) {
            echo '<button class="btn btn-block btn-success" style="width: 100px; margin-top: 10px;" data-toggle="modal" onclick="LoadOrder(0,\'Add\')" id="btnAdd">Agregar</button>';
          }
          ?>
        </div><!-- /.box-header -->
        <div class="box-body">
          <table id="provid" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th width="20%">Acciones</th>
                <th>Fecha</th>
                <th>Observación</th>
                <th>Estado</th>
              </tr>
            </thead>
            <tbody>
              <?php
                if($orders) {
                	foreach($orders as $p)
      		        {
  	                echo '<tr>';
  	                echo '<td>';

                    if (strpos($permission,'Edit') !== false) {
  	                	echo '<i class="fa fa-fw fa-pencil" style="color: #f39c12; cursor: pointer; margin-left: 15px;" onclick="LoadOrder('.$p['ocId'].',\'Edit\')"></i>';
                    }

                    if (strpos($permission,'Del') !== false) {
  	                	echo '<i class="fa fa-fw fa-times-circle" style="color: #dd4b39; cursor: pointer; margin-left: 15px;" onclick="LoadOrder('.$p['ocId'].',\'Del\')"></i>';
                    }

                    if (strpos($permission,'View') !== false) {
  	                	echo '<i class="fa fa-fw fa-search" style="color: #3c8dbc; cursor: pointer; margin-left: 15px;" onclick="LoadOrder('.$p['ocId'].',\'View\')"></i>';
                    }

  	                echo '</td>';
  	                echo '<td style="text-align: right">'.$p['ocFecha'].'</td>';

                    echo '<td style="text-align: left">'.$p['ocObservacion'].'</td>';


                    echo '<td style="text-align: center">';
                    switch($p['ocEstado']){
                      case 'AC':
                        echo '<small class="label pull-left bg-green">Activo</small>';
                        break;

                      case 'IN':
                        echo '<small class="label pull-left bg-red">Inactivo</small>';
                        break;
                    }
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
    $('#provid').DataTable({
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

  var idPro = 0;
  var acPro = '';

  function LoadOrder(id_, action){
  	idPro = id_;
  	acPro = action;
  	LoadIconAction('modalAction',action);
  	WaitingOpen('Cargando Proveedor');
      $.ajax({
          	type: 'POST',
          	data: { id : id_, act: action },
    		    url: '/order/getOrder',
    		    success: function(result){
			                WaitingClose();
			                $("#modalBodyOrder").html(result.html);
			                setTimeout("$('#modalOrder').modal('show')",800);
                      $(".select2").select2({
                        allowClear: true
                      });
    					},
    		    error: function(result){
    					WaitingClose();
    					alert("error");
    				},
          	dataType: 'json'
    		});
  }


  $('#btnSave').click(function(){

  	if(acPro == 'View')
  	{
  		$('#modalOrder').modal('hide');
  		return;
  	}

  	var hayError = false;
    if($('#prvRazonSocial').val() == '')
    {
    	hayError = true;
    }

    if($('#prvDocumento').val() == '')
    {
      hayError = true;
    }

    if(hayError == true){
    	$('#error').fadeIn('slow');
    	return;
    }

    $('#error').fadeOut('slow');
    WaitingOpen('Guardando cambios');
    	$.ajax({
          	type: 'POST',
          	data: {
                    id : idPro,
                    act: acPro,
                    nom: $('#prvNombre').val(),
                    ape: $('#prvApellido').val(),
                    rz: $('#prvRazonSocial').val(),
                    tp: $('#docId').val(),
                    doc: $('#prvDocumento').val(),
                    dom: $('#prvDomicilio').val(),
                    mai: $('#prvMail').val(),
                    est: $('#prvEstado').val(),
                    tel: $('#prvTelefono').val()
                  },
    		url: 'index.php/Order/setOrder',
    		success: function(result){
                			WaitingClose();
                			$('#modalOrder').modal('hide');
                			setTimeout("cargarView('Order', 'index', '"+$('#permission').val()+"');",1000);
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
<div class="modal fade" id="modalOrder" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span id="modalAction"> </span> Orden de Compra</h4>
      </div>
      <div class="modal-body" id="modalBodyOrder">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnSave">Guardar</button>
      </div>
    </div>
  </div>
</div>
