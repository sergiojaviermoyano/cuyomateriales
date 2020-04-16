<input type="hidden" id="permission" value="<?php echo $permission; ?>">
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Administración Cartera de Cheques</h3>
          <?php
            if (strpos($permission,'Add') !== false) {
              echo '<button class="btn btn-block btn-success" style="width: 100px; margin-top: 10px;" data-toggle="modal" onclick="LoadCheck(0,\'Add\')" id="btnAdd">Agregar</button>';
            }
          ?>
        </div><!-- /.box-header -->
        <div class="box-body">
          <table id="checks" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th width="10%">Acciones</th>
                <th width="20%">Número</th>
                <th width="10%">Banco</th>
                <th width="10%">Importe</th>
                <th width="10%">Vencimiento</th>
                <th width="10%">Estado</th>
              </tr>
            </thead>
            <tbody>
              <?php
                if(isset($list)){
                  if($list != false)
                	foreach($list as $c)
      		        {
  	                echo '<tr>';                  
                    echo '<td>';
                    if (strpos($permission,'View') !== false) {
                      echo '<i  class="fa fa-fw fa-search" style="color: #3c8dbc; cursor: pointer; margin-left: 15px;" onclick="LoadCheck('.$c['cheId'].',\'View\')" title="Consultar"></i>';
                    }

                    if (strpos($permission,'Dep') !== false && $c['cheEstado'] === 'AC') {
                        echo '<i class="fa fa-fw fa-thumb-tack" style="color: #f39c12; cursor: pointer; margin-left: 15px;" onclick="LoadCheck('.$c['cheId'].',\'Dep\')" title="Depositar"></i> ';
                    }
  	                		
  	                echo '</td>';
  	                echo '<td style="text-align: right">'.$c['cheNumero'].'</td>';
                    echo '<td>'.$c['bancoDescripcion'].'</td>';
                    echo '<td style="text-align: right">'.$c['cheImporte'].'</td>';
                    $date = date_create($c['cheVencimiento']);                    
                    echo '<td style="text-align: center">'.date_format($date, 'd-m-Y').'</td>';
                    
                    echo '<td style="text-align: center">';
                    switch($c['cheEstado']){
                      case 'AC':
                        echo '<small class="label bg-green">Activo</small>';
                        break;
                      case 'UT':
                        echo '<small class="label bg-blue">Utilizado</small>';
                        break;
                      case 'DE':
                        echo '<small class="label bg-yellow">Depositado</small>';
                        break;
                    } 
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
    $('#checks').DataTable({
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

  
  var id_ = 0;
  var action = '';
  
  function LoadCheck(id__, action_){
  	id_ = id__;
  	action = action_;
  	LoadIconAction('modalActionCheque',action);
    WaitingOpen('Cargando Cheque');
      $.ajax({
          	type: 'POST',
          	data: { id : id_, act: action },
    		url: 'index.php/check/getCheck',
    		success: function(result){
			                WaitingClose();
			                $("#modalBodyCheque").html(result.html);
                      $('#chequeImporte').maskMoney({allowNegative: true, thousands:'', decimal:'.'});
                      $(".select2").select2();
                      $('#cliId').select2({
                          dropdownParent: $('#modalCheque')
                      });
                      $('#bancoId').select2({
                          dropdownParent: $('#modalCheque')
                      });
                      if(action == 'Add')
                        $('#chequeVto').datepicker({});
			                setTimeout("$('#modalCheque').modal('show');",800);
                    
    					},
    		error: function(result){
    					WaitingClose();
    					alert("error");
    				},
          	dataType: 'json'
    		});
  }

  
  $('#btnSaveCheque').click(function(){
  	
  	if(action == 'View')
  	{
  		$('#modalCheque').modal('hide');
  		return;
  	}
    var error = '';

    if($('#cliId').val() == '' || $('#cliId').val() == -1 || $('#cliId').val() == 0){
      error = '* Debe indicar el cliente que le entrego el cheque.<br>';
    }

    if($('#chequeNro').val() == '' ){
      error += '* Debe indicar el número de cheque a cargar.<br>';
    }

    if($('#chequeImporte').val() == '' ){
      error += '* Debe indicar el importe del cheque.<br>';
    }

    if($('#chequeVto').val() == '' ){
      error += '* Debe indicar el vencimiento del cheque.<br>';
    }
    
    if($('#bancoId').val() == '' || $('#bancoId').val() == -1 || $('#bancoId').val() == 0){
      error += '* Debe indicar el banco del cheque a cargar.<br>';
    }

    var radioValue = '';
    if(action == 'Dep'){
      radioValue = $("input[name='cheUso']:checked"). val();
      if(radioValue == '' || radioValue == undefined){
        error += '* Debe indicar la disposición del cheque.<br>';
      }

      if($('#cheDetalle').val() == ''){
        error += '* Debe indicar un detalle sobre la disposición del cheque.<br>';
      }
    }

    if(error != ''){
      $("#errorCheque").find("p").html(error);
    	$('#errorCheque').fadeIn('slow');
      setTimeout("$('#errorCheque').fadeOut('slow');",5000);
    	return false;
    }


    //$('#error').fadeOut('slow');
    WaitingOpen('Guardando cambios');
    	$.ajax({
          	type: 'POST',
          	data: { 
                    id :    id_, 
                    act:    action,
                    cliId:  $('#cliId').val(),
                    cheNro: $('#chequeNro').val(),
                    cheImp: $('#chequeImporte').val(),
                    cheVto: $('#chequeVto').val(),
                    bancoI: $('#bancoId').val(),
                    cheObs: $('#cheObservacion').val(),
                    cheDet: action == 'Dep' ? $('#cheDetalle').val() : '',
                    cheDis: radioValue
                  },
    		url: 'index.php/check/setCheck', 
    		success: function(result){
                			WaitingClose();
                			$('#modalCheque').modal('hide');
                			setTimeout("cargarView('check', 'index', '"+$('#permission').val()+"');",1000);
    					},
    		error: function(result){
    					WaitingClose();
    					ProcesarError(result.responseText, 'modalCheque');
    				},
          	dataType: 'json'
    		});
  });

</script>

<!-- Modal -->
<div class="modal fade" id="modalCheque" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="width: 60%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabelPago"><span id="modalActionCheque"><i class="fa fa-fw  fa-dollar text-green"></i> </span> Cheque</h4> 
      </div>
      <div class="modal-body" id="modalBodyCheque">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnSaveCheque">Guardar</button>
      </div>
    </div>
  </div>
</div>