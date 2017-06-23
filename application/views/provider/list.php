<input type="hidden" id="permission" value="<?php echo $permission;?>">
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Proveedores</h3>
          <?php
          if (strpos($permission,'Add') !== false) {
            echo '<button class="btn btn-block btn-success" style="width: 100px; margin-top: 10px;" data-toggle="modal" onclick="LoadPro(0,\'Add\')" id="btnAdd">Agregar</button>';
          }
          ?>
        </div><!-- /.box-header -->
        <div class="box-body">
          <table id="provid" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th width="20%">Acciones</th>
                <th>Proveedor</th>
                <th>Teléfono</th>
                <th>Correo</th>
              </tr>
            </thead>
            <tbody>
              <?php
              /*
                if($list) {
                	foreach($list as $p)
      		        {
  	                echo '<tr>';
  	                echo '<td>';

                    if (strpos($permission,'Edit') !== false) {
  	                	echo '<i class="fa fa-fw fa-pencil" style="color: #f39c12; cursor: pointer; margin-left: 15px;" onclick="LoadPro('.$p['prvId'].',\'Edit\')"></i>';
                    }

                    if (strpos($permission,'Del') !== false) {
  	                	echo '<i class="fa fa-fw fa-times-circle" style="color: #dd4b39; cursor: pointer; margin-left: 15px;" onclick="LoadPro('.$p['prvId'].',\'Del\')"></i>';
                    }

                    if (strpos($permission,'View') !== false) {
  	                	echo '<i class="fa fa-fw fa-search" style="color: #3c8dbc; cursor: pointer; margin-left: 15px;" onclick="LoadPro('.$p['prvId'].',\'View\')"></i>';
                    }

  	                echo '</td>';
                    echo '<td style="text-align: left">'.$p['prvRazonSocial'].'</td>';
  	                echo '<td style="text-align: right">'.$p['prvTelefono'].'</td>';
                    echo '<td style="text-align: left">'.$p['prvMail'].'</td>';
  	                echo '</tr>';

      		        }

                } */
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
      "processing": true,
      "serverSide": true,
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true,
      'ajax':{
        "dataType": 'json',
        "method": "POST",
        "url":'index.php/provider/listing',
        "dataSrc": function (json) {
          var output=[];
          var permission=$("#permission").val();
          permission= permission.split('-');
            $.each(json.data,function(key,item){
              var td_1="";

                          if(permission.indexOf("Edit")>0 ){
                            td_1+='<i  class="fa fa-fw fa-pencil" style="color: #f39c12; cursor: pointer; margin-left: 15px;" onclick="LoadPro('+item.prvId+',\'Edit\')"></i>';
                          }

                          if(permission.indexOf("Del")>0){
                            td_1+='<i  class="fa fa-fw fa-times-circle" style="color: #dd4b39; cursor: pointer; margin-left: 15px;" onclick="LoadPro('+item.prvId+',\'Del\')"></i>';
                          }

                          if(permission.indexOf("View")>0){
                            td_1+='<i  class="fa fa-fw fa-search" style="color: #3c8dbc; cursor: pointer; margin-left: 15px;" onclick="LoadPro('+item.prvId+',\'View\')"></i>';
                          }
              var td_2=item.prvRazonSocial;
              var td_3=item.prvTelefono;
              var td_4="";
              switch (item.artEstado) {
                case 'AC':{
                  td_4='<small class="label pull-left bg-green">Activa</small>';
                  break;
                }
                case 'IN':{
                  td_4='<small class="label pull-left bg-red">Inactiva</small>';
                  break;
                }
                case 'FA':{
                  td_4='<small class="label pull-left bg-blue">Facturada</small>';
                  break;
                }
                default:{
                  td_4='';
                  break;
                }
              }

              output.push([td_1,td_2,td_3,td_4]);
            });

            return output;
        },
        error:function(the_error){
          console.debug(the_error);
        }
      },
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

  function LoadPro(id_, action){
  	idPro = id_;
  	acPro = action;
  	LoadIconAction('modalAction',action);
  	WaitingOpen('Cargando Proveedor');
      $.ajax({
          	type: 'POST',
          	data: { id : id_, act: action },
    		    url: 'index.php/provider/getProvider',
    		    success: function(result){
			                WaitingClose();
			                $("#modalBodyProvider").html(result.html);
			                setTimeout("$('#modalProvider').modal('show')",800);
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
  		$('#modalProvider').modal('hide');
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
    		url: 'index.php/provider/setProvider',
    		success: function(result){
                			WaitingClose();
                			$('#modalProvider').modal('hide');
                			setTimeout("cargarView('Provider', 'index', '"+$('#permission').val()+"');",1000);
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
<div class="modal fade" id="modalProvider" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span id="modalAction"> </span> Proveedor</h4>
      </div>
      <div class="modal-body" id="modalBodyProvider">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnSave">Guardar</button>
      </div>
    </div>
  </div>
</div>
