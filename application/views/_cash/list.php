<input type="hidden" id="permission" value="<?php echo $permission;?>">
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Movimientos de Cuenta Corriente</h3>
            <div class="row">
              <div class="col-xs-2">
              <?php
              if (strpos($permission,'Add') !== false) {
                echo '<button class="btn btn-block btn-success" style="width: 100px; margin-top: 10px;" data-toggle="modal" onclick="LoadCtaCte(0,\'Add\')" id="btnAdd">Agregar</button>';
              }

              if (strpos($permission,'Saldo') !== false) {
                echo '</div>
                      <div class="col-xs-2">
                      <button class="btn btn-block btn-info" style="width: 100px; margin-top: 10px;" data-toggle="modal" onclick="LoadSaldoCtaCte()" id="btnBalance">Saldo</button>';
              }
              ?>
              </div>
            </div>
        </div><!-- /.box-header -->
        <div class="box-body">
          <table id="credit" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th width="10%">Acciones</th>
                <th>Número</th>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Descripción</th>
                <th>Usuario</th>
                <th>Debe</th>
                <th>Haber</th>
              </tr>
            </thead>
            <tbody>
              <?php
                if(count($list) > 0) {                  
                	foreach($list as $m)
      		        {
  	                echo '<tr>';
  	                echo '<td>';
                    /*
                    if (strpos($permission,'Edit') !== false) {
  	                	echo '<i class="fa fa-fw fa-pencil" style="color: #f39c12; cursor: pointer; margin-left: 15px;" onclick="LoadStk('.$s['stkId'].',\'Edit\')"></i>';
                    }
                    */
                    if (strpos($permission,'Del') !== false) {
  	                	echo '<i class="fa fa-fw fa-times-circle" style="color: #dd4b39; cursor: pointer; margin-left: 15px;" onclick="LoadCtaCte('.$m['crdId'].',\'Del\')"></i>';
                    }
                    
                    if (strpos($permission,'View') !== false) {
  	                	echo '<i class="fa fa-fw fa-search" style="color: #3c8dbc; cursor: pointer; margin-left: 15px;" onclick="LoadCtaCte('.$m['crdId'].',\'View\')"></i>';
                    }
  	                echo '</td>';
                    echo '<td>'.str_pad($m['crdId'], 10, "0", STR_PAD_LEFT).'</td>';
                    $date = date_create($m['crdDate']);
                    //echo date_format($date, 'Y-m-d H:i:s');
                    echo '<td style="text-align: center">'.date_format($date, 'd-m-Y H:i').'</td>';
                    echo '<td style="text-align: left">'.$m['cliLastName'].', '.$m['cliName'].'</td>';
                    echo '<td style="text-align: left">'.$m['crdDescription'].'</td>';
                    echo '<td style="text-align: left">'.$m['usrNick'].'</td>';
  	                echo '<td style="text-align: right">';
                      if($m['crdDebe'] > 0)
                        echo $m['crdDebe'];
                    echo '</td>';
                    echo '<td style="text-align: right">';
                      if($m['crdHaber'] > 0)
                        echo $m['crdHaber'];
                    echo '</td>';
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
    $('#credit').DataTable({
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

  var idCrd = 0;
  var acCrd = '';
  
  function LoadCtaCte(id_, action){
  	idCrd = id_;
  	acCrd = action;
  	LoadIconAction('modalAction',action);
  	WaitingOpen('Cargando Movimiento');
      $.ajax({
          	type: 'POST',
          	data: { id : id_, act: action },
    		    url: 'index.php/cash/getMotion', 
    		    success: function(result){
			                WaitingClose();
			                $("#modalBodyCash").html(result.html);
			                setTimeout("$('#modalCash').modal('show')",800);
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

  function LoadSaldoCtaCte(){
    acCrd = 'View';
    LoadIconAction('modalAction','View');
    WaitingOpen('Cargando...');
      $.ajax({
            type: 'POST',
            data: null,
            url: 'index.php/cash/getBalance', 
            success: function(result){
                      WaitingClose();
                      $("#modalBodyCash").html(result.html);
                      setTimeout("$('#modalCash').modal('show')",800);
                      $(".select2").select2({
                        allowClear: true
                      });
                      $('#cliId').change(function(){
                        var importe = $(this).children('option:selected').data('balance');
                        if(importe < 0){
                          $('#textBalance').html('<i class="fa fa-fw fa-plus" style="color: #00a65a"></i>');
                          $('#importBalance').html(importe.replace('-',''));
                        }
                        else{
                          if(importe == 0){
                            $('#textBalance').html('<i class="fa fa-fw fa-check" style="color: #3c8dbc"></i>');
                            $('#importBalance').html(importe);
                          }
                          else{
                            $('#textBalance').html('<i class="fa fa-fw fa-minus" style="color: #dd4b39"></i>');
                            $('#importBalance').html(importe);
                          }
                        }
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

  	if(acCrd == 'View')
  	{
  		$('#modalCash').modal('hide');
  		return;
  	}

debugger;
  	var hayError = false;
    if(acCrd != 'Del')
    {
      if($('#crdDescription').val() == '')
      {
      	hayError = true;
      }

      if($('#crdNote').val() == '')
      {
        hayError = true;
      }

      if($('#crdDebe').val() == '' && $('#crdHaber').val() == '')
      {
        hayError = true;
      }
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
                    id : idCrd, 
                    act: acCrd, 
                    cliId: $('#cliId').val(),
                    desc: $('#crdDescription').val(),
                    debe: $('#crdDebe').val(),
                    haber: $('#crdHaber').val(),
                    note: $('#crdNote').val()
                  },
    		url: 'index.php/cash/setMotion', 
    		success: function(result){
                			WaitingClose();
                			$('#modalCash').modal('hide');
                			setTimeout("cargarView('Cash', 'index', '"+$('#permission').val()+"'); $('.my-colorpicker').colorpicker();",1000);
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
<div class="modal fade" id="modalCash" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span id="modalAction"> </span> Movimiento</h4> 
      </div>
      <div class="modal-body" id="modalBodyCash">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnSave">Guardar</button>
      </div>
    </div>
  </div>
</div>