<input type="hidden" id="permission" value="<?php echo $permission;?>">
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Artículos</h3>
          <?php
          if (strpos($permission,'Add') !== false) {
            echo '<button class="btn btn-block btn-success" style="width: 100px; margin-top: 10px;" data-toggle="modal" onclick="LoadArt(0,\'Add\')" id="btnAdd">Agregar</button>';
          }
          ?>
        </div><!-- /.box-header -->
        <div class="box-body">
          <table id="articles" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th width="20%">Acciones</th>
                <th width="5%">Código</th>
                <th>Descripción</th>
                <!--<th>P.Costo</th>-_>
                <!--<th>P.Venta</th>-->
                <th width="5%">Estado</th>
              </tr>
            </thead>
            <tbody>
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
    $('#articles').DataTable({
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
        //"contentType": "application/json; charset=utf-8",
        "method": "POST",
        "url":'index.php/article/listing',
        "dataSrc": function (json) {
          //console.debug("==> json: %o",json);
          var output=[];
          var permission=$("#permission").val();
          permission= permission.split('-');

          $.each(json.data,function(key,item){
            var td_1="";

            if(permission.indexOf("Edit")>0 ){
              td_1+='<i  class="fa fa-fw fa-pencil" style="color: #f39c12; cursor: pointer; margin-left: 15px;" onclick="LoadArt('+item.artId+',\'Edit\')"></i>';
            }

            if(permission.indexOf("Del")>0){
              td_1+='<i  class="fa fa-fw fa-times-circle" style="color: #dd4b39; cursor: pointer; margin-left: 15px;" onclick="LoadArt('+item.artId+',\'Del\')"></i>';
            }

            if(permission.indexOf("View")>0){
              td_1+='<i  class="fa fa-fw fa-search" style="color: #3c8dbc; cursor: pointer; margin-left: 15px;" onclick="LoadArt('+item.artId+',\'View\')"></i>';
            }
            var td_2=item.artBarCode;
            var td_3=item.artDescription;
            var td_5=item.artEstado;
            switch (item.artEstado) {
              case 'AC':{
                td_5='<small class="label pull-left bg-green">Activa</small>';
                break;
              }
              case 'IN':{
                td_5='<small class="label pull-left bg-red">Inactiva</small>';
                break;
              }
              case 'FA':{
                td_5='<small class="label pull-left bg-blue">Facturada</small>';
                break;
              }
              default:{
                td_5='';
                break;
              }
            }
            output.push([td_1,td_2,td_3,td_5]);
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

  var idArt = 0;
  var acArt = '';

  function LoadArt(id_, action){
  	idArt = id_;
  	acArt = action;
  	LoadIconAction('modalAction',action);
  	WaitingOpen('Cargando Artículo');
      $.ajax({
          	type: 'POST',
          	data: { id : id_, act: action },
    		url: 'index.php/article/getArticle',
    		success: function(result){
			                WaitingClose();
			                $("#modalBodyArticle").html(result.html);
                      $("#artCantBox").maskMoney({allowNegative: true, thousands:'', decimal:'.'});
                      $("#artCoste").maskMoney({allowNegative: true, thousands:'', decimal:'.'});
                      $("#artMargin").maskMoney({allowNegative: true, thousands:'', decimal:'.'});
                      CalcularPrecio();
			                setTimeout("$('#modalArticle').modal('show')",800);
                      $("[data-mask]").inputmask();
    					},
    		error: function(result){
    					WaitingClose();
    					alert("error");
    				},
          	dataType: 'json'
    		});
  }


  $('#btnSave').click(function(){

  	if(acArt == 'View')
  	{
  		$('#modalArticle').modal('hide');
  		return;
  	}

  	var hayError = false;
    var error_message="";
    if($('#artBarCode').val() == '')
    {
      hayError = true;
      error_message += " * Por Favor, debe Ingresar un Código de Artículo. <br> ";
    }

    if($('#artDescription').val() == '')
    {
      hayError = true;
      error_message += " * Por Favor, debe Ingresar una Descripción. <br> ";

    }

    if($('#artCoste').val() == '')
    {
      hayError = true;
      error_message += " * Por Favor, debe Ingresar Costo. <br> ";
    }

    if($('#subrId').val() == '')
    {
      hayError = true;
      error_message += " * Por Favor, debe Seleccionar un Sub Rubro. <br> ";

    }

    if($('#ivaId').val() == ''){
      hayError = true;
      error_message += " * Por Favor, debe Seleccionar una Condición de IVA. <br> ";
    }

    /*
    if($('#artMinimo').val() == ''){
      hayError = true;
      error_message += " * Por Favor, debe Seleccionar un Mínimo. <br> ";
    }

    if($('#artMedio').val() == ''){
      hayError = true;
      error_message += " * Por Favor, debe Seleccionar un Medio. <br> ";
    }

    if($('#artMaximo').val() == ''){
      hayError = true;
      error_message += " * Por Favor, debe Seleccionar un Maximo. <br> ";
    }
    */


    if(hayError == true){
      $("#errorArt").find("p").html(error_message);
    	$('#errorArt').fadeIn('slow');
    	return false;
    }

    $('#errorArt').fadeOut('slow');
    WaitingOpen('Guardando cambios');
    	$.ajax({
          	type: 'POST',
          	data: {
                    id :      idArt,
                    act:      acArt,
                    code:     $('#artBarCode').val(),
                    name:     $('#artDescription').val(),
                    marg:     $('#artMargin').val(),
                    margP:    $('#artMarginIsPorcent').prop('checked'),
                    price:    $('#artCoste').val(),
                    status:   $('#artEstado').val(),
                    box:      $('#artIsByBox').prop('checked'),
                    boxCant:  $('#artCantBox').val(),
                    fraction: $('#artSeFracciona').prop('checked'),
                    subrId:   $("#subrId").val(),
                    ivaId:    $("#ivaId").val(),
                    artMinimo:   $("#artMinimo").val(),
                    artMedio:   $("#artMedio").val(),
                    artMaximo:   $("#artMaximo").val(),
                  },
    		url: 'index.php/article/setArticle',
    		success: function(result){
                			WaitingClose();
                			$('#modalArticle').modal('hide');
                			setTimeout("cargarView('Article', 'index', '"+$('#permission').val()+"');",1000);
    					},
    		error: function(result){
    					WaitingClose();
              ProcesarError(result.responseText, 'modalArticle');
    				},
          	dataType: 'json'
    		});
  });

</script>

<!-- Modal -->
<div class="modal fade" id="modalArticle" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content" >
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span id="modalAction"> </span> Artículo</h4>
      </div>
      <div class="modal-body" id="modalBodyArticle" style="    min-height: 650px;">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnSave">Guardar</button>
      </div>
    </div>
  </div>
</div>