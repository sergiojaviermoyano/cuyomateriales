<input type="hidden" id="permission" value="<?php echo $permission;?>">
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Grupos</h3>
          <?php
          if (strpos($permission,'Add') !== false) {
            echo '<button class="btn btn-block btn-success" style="width: 100px; margin-top: 10px;" data-toggle="modal" onclick="LoadGrp(0,\'Add\')" id="btnAdd" >Agregar</button>';
          }
          ?>
        </div><!-- /.box-header -->
        <div class="box-body">
          <table id="groups" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th width="20%">Acciones</th>
                <th>Nombre</th>
              </tr>
            </thead>
            <tbody>
              <?php
              	foreach($list as $g)
		        {
		                echo '<tr>';
		                echo '<td>';
                    if (strpos($permission,'Edit') !== false) {
		                	echo '<i class="fa fa-fw fa-pencil" style="color: #f39c12; cursor: pointer; margin-left: 15px;" onclick="LoadGrp('.$g['grpId'].',\'Edit\')"></i>';
                    }
		                if (strpos($permission,'Del') !== false) {
                      echo '<i class="fa fa-fw fa-times-circle" style="color: #dd4b39; cursor: pointer; margin-left: 15px;" onclick="LoadGrp('.$g['grpId'].',\'Del\')"></i>';
                    }
		                if (strpos($permission,'View') !== false) {
                      echo '<i class="fa fa-fw fa-search" style="color: #3c8dbc; cursor: pointer; margin-left: 15px;" onclick="LoadGrp('.$g['grpId'].',\'View\')"></i>';
                    }
		                echo '</td>';
		                echo '<td style="text-align: left">'.$g['grpName'].'</td>';
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
    $('#groups').DataTable({
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

  var idGrupo = 0;
  var acGrupo = '';

  function LoadGrp(id_, action){
  	idGrupo = id_;
  	acGrupo = action;
  	LoadIconAction('modalAction',action);
  	WaitingOpen('Cargando menu');
      $.ajax({
          	type: 'POST',
          	data: { id : id_, act: action },
    		url: 'index.php/group/getMenu', 
    		success: function(result){
			                WaitingClose();
			                $("#modalBodyGrp").html(result.html);
			                setTimeout("$('#modalGrp').modal('show')",800);
    					},
    		error: function(result){
    					WaitingClose();
    					ProcesarError(result.responseText, 'modalGrp');
    				},
          	dataType: 'json'
    		});
  }

  $('#btnSave').click(function(){
  	
  	if(acGrupo == 'View')
  	{
  		$('#modalGrp').modal('hide');
  		return;
  	}

  	var hayError = true;
  	var permission = [];
  	$('#permission :checked').each(function() {
        hayError = false;
        permission.push($(this).attr('id'));
    });

    if($('#grpName').val() == '')
    {
    	hayError = true;
    }

    if(hayError == true){
    	$('#errorGrp').fadeIn('slow');
    	return;
    }

    $('#errorGrp').fadeOut('slow');
    WaitingOpen('Guardando cambios');
    	$.ajax({
          	type: 'POST',
          	data: { id : idGrupo, act: acGrupo, name: $('#grpName').val(), options: permission },
    		url: 'index.php/group/setMenu', 
    		success: function(result){
                			WaitingClose();
                			$('#modalGrp').modal('hide');
                			setTimeout("cargarView('group', 'index', '"+$('#permission').val()+"');",1000);
    					},
    		error: function(result){
    					WaitingClose();
    					ProcesarError(result.responseText, 'modalGrp');
    				},
          	dataType: 'json'
    		});
  });
</script>


<!-- Modal -->
<div class="modal fade" id="modalGrp" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span id="modalAction"> </span> Grupo</h4> 
      </div>
      <div class="modal-body" id="modalBodyGrp">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnSave">Guardar</button>
      </div>
    </div>
  </div>
</div>