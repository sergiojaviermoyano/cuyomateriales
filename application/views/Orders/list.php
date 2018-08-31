<input type="hidden" id="permission" value="<?php echo $permission;?>">
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Ordenes de Compra</h3>
          <br>

          <?php
          if (strpos($permission,'Add') !== false) {
            echo '<button class="btn  btn-success" style="" data-toggle="modal" onclick="LoadOrder(0,\'Add\')" id="btnAdd">Agregar</button>';
          }
          ?>

        </div><!-- /.box-header -->
        <div class="box-body">
          <table id="order_table" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th width="170px">Acciones</th>
                <th width="1px"></th>
                <th>Cliente</th>
                <th>Observación</th>
                <th width="170px">Fecha</th>
                <th>Estado</th>
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

    var datatable_es={
    "sProcessing":     "Procesando...",
    "sLengthMenu":     "Mostrar _MENU_ registros",
    "sZeroRecords":    "No se encontraron resultados",
    "sEmptyTable":     "Ningún dato disponible en esta tabla",
    "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
    "sInfoPostFix":    "",
    "sSearch":         "Buscar:",
    "sUrl":            "",
    "sInfoThousands":  ",",
    "sLoadingRecords": "Cargando...",
    "oPaginate": {
        "sFirst":    "Primero",
        "sLast":     "Último",
        "sNext":     "Siguiente",
        "sPrevious": "Anterior"
    },
    "oAria": {
        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    }
};

    var dataTable= $('#order_table').DataTable({
      "processing": true,
      "serverSide": true,
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true,
      "language":datatable_es,
      'ajax':{
          "dataType": 'json',
          //"contentType": "application/json; charset=utf-8",
          "method": "POST",
          "url":'index.php/order/listingOrders',
          "dataSrc": function (json) {
            var output=[];
            var permission=$("#permission").val();
            permission= permission.split('-');
            $.each(json.data,function(index,item){
              var td_1="";
                  if(permission.indexOf("Rem")>0){
                    td_1+='<span class="label label-primary" style="margin-right: 5px; cursor: pointer;" title="Remito" onclick="PrintRemito('+item.ocId+')">R</span>';
                  }
                  
                  if(permission.indexOf("Imprimir")>0){
                    td_1+='<i class="fa fa-fw fa-print" style="color: #A4A4A4; cursor: pointer; margin-left: 15px;" onclick="Print('+item.ocId+')"></i>';
                  }

                  if(permission.indexOf("Edit")>0  && item.ocEstado=='AC'){
                    td_1+='<i  class="fa fa-fw fa-pencil" style="color: #f39c12; cursor: pointer; margin-left: 15px;" onclick="LoadOrder('+item.ocId+',\'Edit\')"></i>';
                  }

                  if(permission.indexOf("Del")>0){
                    td_1+='<i  class="fa fa-fw fa-times-circle" style="color: #dd4b39; cursor: pointer; margin-left: 15px;" onclick="LoadOrder('+item.ocId+',\'Del\')"></i>';
                  }

                  if(permission.indexOf("View")>0){
                    td_1+='<i  class="fa fa-fw fa-search" style="color: #3c8dbc; cursor: pointer; margin-left: 15px;" onclick="LoadOrder('+item.ocId+',\'View\')"></i>';
                  }

                  if(permission.indexOf("Ent")>0 && item.ocEstado=='AC'){
                    td_1+='<i  class="fa fa-fw fa-truck" style="color: #bc3c5e; cursor: pointer; margin-left: 15px;" onclick="LoadOrder('+item.ocId+',\'Ent\')"></i>';
                  }
              var td_1_2 = "";
                  if(item.ocEsPresupuesto==1){
                   td_1_2+= '<small class="label pull-left bg-navy" style="font-size: 14px;" title="Presupuesto">P</small>';
                  }

              var td_1_3="";
                 td_1_3+= item.cliApellido + ' ' + item.cliNombre;

              var td_2="";
                 td_2+= item.ocObservacion;//item.ocObservacion;
              
              var date = new Date(item.ocFecha);
                        
              var month = date.getMonth() + 1;
              var td_3=("0"+date.getDate()).slice(-2)+'-'+("0"+ month).slice(-2)+'-'+("0"+date.getFullYear()).slice(-4)+' '+("0"+date.getHours()).slice(-2)+':'+("0"+date.getMinutes()).slice(-2);
              
              var td_4="";

              switch (item.ocEstado) {
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
                case 'EN':{
                  td_4='<small class="label pull-left bg-red">Entegada</small>';
                  break;
                }
                default:{
                  td_4='';
                  break;
                }
              }
              //for(i=0;i<=100000;i++){
                output.push([td_1,td_1_2,td_1_3,td_2,td_3,td_4]);

              //}

            });
            return output;
          },
          error:function(the_error){
            console.debug(the_error);
          }
        }

    });
  });

  var idOrder = 0;
  var acOrder = '';

  function LoadOrder(id_, action){
  	idOrder = id_;
  	acOrder = action;
  	LoadIconAction('modalAction',action);
  	WaitingOpen('Cargando Proveedor');
      $.ajax({
          	method: 'POST',
          	data: { id : id_, act: action },
    		    url: 'index.php/order/getOrder',
    		    success: function(result){
			                WaitingClose();
			                $("#modalBodyOrder").html(result.html);
			                setTimeout(function () { $('#modalOrder').modal('show'); }, 1000);
                      setTimeout(function () { $('#cliSearch').focus(); }, 1200);
                      $(".select2").select2({
                        allowClear: true
                      });
                      $("#ocDescuento").maskMoney({allowNegative: false, thousands:'', decimal:'.'});
                      Calcular();
                      if(action != 'Add' ){
                        $('#btnPre').hide();
                        if(action == 'Edit' && $('#ocEsPresupuesto').val() == '1')
                          $('#dejadeserpresupuestolb').show();
                        else
                          $('#dejadeserpresupuestolb').hide();
                      } else {
                        $('#btnPre').show();
                        $('#dejadeserpresupuestolb').hide();
                      }
    					},
    		    error: function(result){
    					WaitingClose();
    					alert("error");
    				},
          	dataType: 'json'
    		});


  }

  function validate(esPresupuesto){
    if(esPresupuesto == 1){
      acOrder = 'Pre';
    }
    var hayError = false;
    if(acOrder == 'View')
    {
      $('#modalOrder').modal('hide');
      return;
    }

    if($('#lpId').val() == -1)
    {
      hayError = true;
    }

    if($('#cliId').val() == "" || $('#cliId').val() == -1)
    {
      hayError = true;
    }

    var items = parseFloat($('#saleItems').html());
    var venta = parseFloat($('#saleTotal').html());
    var redondeo =parseFloat($('#redondeo').val());
    var sale = [];
    if(items > 0 && venta > 0){
      var table = $('#order_detail > tbody> tr');
      table.each(function(r) {
        var object = {
          artId:          parseInt(this.children[6].textContent),
          artCode:        this.children[1].textContent,
          artDescription: this.children[2].textContent,
          artCoste:       parseFloat(this.children[8].textContent),
          artFinal:       parseFloat(this.children[4].textContent),
          venCant:        parseFloat(this.children[3].textContent),
          artVenta:       parseFloat(this.children[7].textContent),
        };

        sale.push(object);
      });
    } else {
      hayError = true;
    }

    var descuento = 0;
    descuento = $('#ocDescuento').val() == "" ? 0 : parseFloat($('#ocDescuento').val());
    if($('#ocDescuentoIsPorcent').prop("checked")){
      if(descuento > 0){
        descuento = (descuento * venta) / 100;
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
                    id : idOrder,
                    act: acOrder,
                    obser:  $('#ocObservacion').val(),
                    cliId_:  $('#cliId').val(),
                    lpId:   $('#lpId').val(),
                    art:    sale,
                    redondeo: redondeo,
                    dejadeserpresupuesto: $('#dejadeserpresupuesto').is(':checked'),
                    descuento: descuento,
                    usrAutDesc: usuarioAutorizaDescuento == 0 ? null : parseInt(usuarioAutorizaDescuento)
                  },
        url: 'index.php/Order/setOrder',
        success: function(result){
                      WaitingClose();
                      $('#modalOrder').modal('hide');
                      setTimeout("cargarView('Order', 'index', '"+$('#permission').val()+"');",1000);
              },
        error: function(result){
              WaitingClose();
              ProcesarError(result.responseText, 'modalOrder');
            },
            dataType: 'json'
        });
  }

  $('#btnPre').click(function(){
    validate(1);
  });
  var usuarioAutorizaDescuento = 0;
  $('#btnSave').click(function(){
    var permission=$("#permission").val();
    permission= permission.split('-');
    //Validar si los descuentos son distintos
    var desc = parseFloat($('#ocDescuento').val() == "" ? 0 : $('#ocDescuento').val());
    var descOrg = parseFloat($('#ocDescuentoOrg').val() == "" ? 0 : $('#ocDescuentoOrg').val());
    if(desc != descOrg){
      //Pedir autorización de descuento
      $('#modalAutorizacion').modal('show');
      setTimeout(function () { $('#autUsuario').focus(); }, 1000);
    } else {
      if(acOrder == 'Add' && permission.indexOf("Ent") > 0){
        $('#modalEntrega').modal('show');
      } else {
        validate(0);
      }
    }
    
  });

  $('#btnAutorizar').click(function(){
      var hayError = false;
      if($('#autUsuario').val() == '')
      {
        hayError = true;
      }

      if($('#autPassword').val() == ''){
        hayError = true;
      }

      if(hayError == true){
        $('#errorAut').fadeIn('slow');
        setTimeout(function () { $('#errorAut').fadeOut('slow'); }, 5000);
        return;
      }

      $('#errorLgn').fadeOut('hide');

      WaitingOpen('Validando datos');
      $.ajax({
          type: 'POST',
          data: { 
                  usr: $('#autUsuario').val(),
                  pas: $('#autPassword').val()
                },
          url: 'index.php/login/validateAut', 
          success: function(result){
                WaitingClose();
                if(result == 0){
                  $('#errorAut').fadeIn('slow');
                  setTimeout(function () { $('#errorAut').fadeOut('slow'); }, 5000);
                }else{
                  usuarioAutorizaDescuento = result;
                  $('#modalAutorizacion').modal('hide');
                  var permission=$("#permission").val();
                  permission= permission.split('-');
                  if(acOrder == 'Add' && permission.indexOf("Ent") > 0){
                    $('#modalEntrega').modal('show');
                  } else {
                    validate(0);
                  }
                }
              },
          error: function(result){
              WaitingClose();
              $('#errorLgn').fadeIn('slow');
              },
          dataType: 'json'
        });
  });

  $('#btnNo').click(function(){
    $('#modalEntrega').modal('hide');
    validate(0);
  });

  $('#btnSi').click(function(){
    $('#modalEntrega').modal('hide');
    acOrder = 'Ent';
    validate(0);
  });

  function Calcular(){
    var table = $('#order_detail > tbody> tr');
    var items = 0;
    var total = 0;
    var descuento = 0;
    table.each(function(r) {
      items += parseFloat(this.children[3].textContent);
      total += parseFloat(this.children[5].textContent);
    });

    if($('#ocDescuentoIsPorcent').prop("checked")){
      descuento = $('#ocDescuento').val() == "" ? 0 : parseFloat($('#ocDescuento').val());
      if(descuento > 0){
        descuento = parseFloat((parseFloat(descuento) * total) / 100).toFixed(2);
        $('#ocDescuentoPorcentImport').html('('+parseFloat(descuento).toFixed(2)+')');
      }else {
        $('#ocDescuentoPorcentImport').html('0.00');
      }
    }else{
      descuento = $('#ocDescuento').val() == "" ? 0 : parseFloat($('#ocDescuento').val());
      $('#ocDescuentoPorcentImport').html('0.00');
    }
    //SubTotal 
    $('#saleTotal').html(parseFloat(total).toFixed(2));

    $('#saleItems').html(items);
    var redondeo=parseFloat(total - descuento).toFixed(0)-parseFloat(total - descuento).toFixed(2);
    $("#modalOrder").find("#redondeo").val(redondeo.toFixed(2));
    if(redondeo>=0){
      $("#label_discount").text("+"+redondeo.toFixed(2));//parseFloat($("#modalOrder").find("#redondeo").val()).toFixed(2));
    }else{
      $("#label_discount").text(redondeo.toFixed(2));//parseFloat($("#modalOrder").find("#redondeo").val()).toFixed(2));
    }

    $('#saleTotalFinal').html(parseFloat(total - descuento + redondeo).toFixed(0)+".00");

    if(total - descuento + redondeo> 0){
      $('#btnPre').prop('disabled', false);
      $('#btnSave').prop('disabled', false);
    } else {
      $('#btnPre').prop('disabled', true);
      $('#btnSave').prop('disabled', true);
    }
  }

  function Print(id__){
    WaitingOpen('Generando reporte...');
    LoadIconAction('modalAction__','Print');
    $.ajax({
            type: 'POST',
            data: {
                    id : id__
                  },
        url: 'index.php/order/printOrder',
        success: function(result){
                      WaitingClose();
                      var url = "./assets/reports/" + result;
                      $('#printDoc').attr('src', url);
                      setTimeout("$('#modalPrint').modal('show')",800);
              },
        error: function(result){
              WaitingClose();
              ProcesarError(result.responseText, 'modalPrint');
            },
            dataType: 'json'
        });
  }

  function PrintRemito(id__){
    WaitingOpen('Generando remito...');
    LoadIconAction('modalAction__','Print');
    $.ajax({
            type: 'POST',
            data: {
                    id : id__
                  },
        url: 'index.php/order/printRemito',
        success: function(result){
                      WaitingClose();
                      var url = "./assets/reports/" + result;
                      $('#printDoc').attr('src', url);
                      setTimeout("$('#modalPrint').modal('show')",800);
              },
        error: function(result){
              WaitingClose();
              ProcesarError(result.responseText, 'modalPrint');
            },
            dataType: 'json'
        });
  }

</script>

<!-- Modal -->
<div class="modal fade" id="modalOrder" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span id="modalAction"> </span> Orden de Compra</h4>
      </div>
      <div class="modal-body" id="modalBodyOrder">

      </div>
      <div class="modal-footer">
        <?php
          if (strpos($permission,'Budget') !== false) {
            echo '<button type="button" class="btn btn-warning pull-left" id="btnPre">Presupuesto</button>';
          }
        ?>
        <label class="pull-left" id="dejadeserpresupuestolb">
          <input type="checkbox" id="dejadeserpresupuesto"> Deja de Ser Presupuesto
        </label>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnSave">Guardar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalSearch" tabindex="3000" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="width: 50%">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel"><span id="modalAction__"> </span> Artículo</h4>
      </div>
      <div class="modal-body" id="modalBodySearch">

        <div class="row">
          <div class="col-xs-10 col-xs-offset-1"><center>Producto</center></div>
        </div>
        <div class="row">
          <div class="col-xs-10 col-xs-offset-1"><input type="text" class="form-control" id="artIdSearch" value=""></div>
          <div class="col-xs-1"><img style="display: none" id="loadingIcon" src="<?php  echo base_url();?>assets/images/loading.gif" width="35px"></div>
            <!--
            <input type="text" id="type" />
            <span id="status"></span>
            -->
        </div><br>

        <div class="row" style="max-height:350px; overflow-x: auto;" id="tableRow">
          <div class="col-xs-10 col-xs-offset-1">
            <table id="saleDetailSearch" style="max-height:340px; display: table;" class="table table-bordered" width="100%">
              <tbody>

              </tbody>
            </table>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" onclick="cancelarBusqueda()">Cancelar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalPrint" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="width: 50%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel__"><span id="modalAction__"> </span> Comprobante</h4>
      </div>
      <div class="modal-body" id="modalBodyPrint">
        <div>
          <iframe style="width: 100%; height: 600px;" id="printDoc" src=""></iframe>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="modalEntrega" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel___"><span id="modalAction___"> <i class="fa fa-fw fa-truck" style="color: #bc3c5e;"></i> </span> ¿ Desea Entregar la orden de compra ?</h4>
      </div>
      <div class="modal-body" id="modalBodyEntrega">
        <div>
            <center>
                <button class="btn btn-danger" id="btnNo">No</button>
                <button class="btn-lg btn-success" id="btnSi">Si</button>
            </center>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Cliente -->
<div class="modal fade" id="modalCli" tabindex="3004" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabelCli"><span id="modalActionCli"> </span> Cliente</h4>
      </div>
      <div class="modal-body" id="modalBodyCli">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnSaveCustomer">Aceptar</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="modalAutorizacion" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document" style="width: 30%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel_"><span><i class="fa fa-fw fa-lock" style="color: #00a65a;"></i></span> Autorización</h4>
      </div>
      <div class="modal-body">
          <div class="row">
            <div class="col-xs-12">
              <div class="alert alert-danger alert-dismissable" id="errorAut" style="display: none">
                    <h4><i class="icon fa fa-ban"></i> Error!</h4>
                    <p>Los datos ingresados no estan autorizados para hacer un descuento.<br></p>
                </div>
            </div>
          </div>
          <input class="form-control" id="autUsuario" placeholder="Usuario" type="text"><br>
          <input class="form-control" id="autPassword" placeholder="Contraseña" type="password">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnAutorizar">Autorizar</button>
      </div>
    </div>
  </div>
</div>

<script>

$('#btnSaveCustomer').click(function(){
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
      setTimeout(function () { $('#errorCust').fadeOut('slow'); }, 5000);
      return;
    }

    WaitingOpen('Creando Cliente');
      $.ajax({
            type: 'POST',
            data: { 
                    id : 0, 
                    act: 'Add', 
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
        url: 'index.php/customer/setCustomer2', 
        success: function(result){
                      WaitingClose();
                      $('#modalCli').modal('hide');
                      if(result != false){
                        var data = {
                            id: result,
                            text: $('#cliApellido').val() + ' ' + $('#cliNombre').val()
                        };
                        var newOption = new Option(data.text, data.id, false, false);
                        $('#cliId').append(newOption).trigger('change');
                      }
              },
        error: function(result){
              WaitingClose();
              alert("error");
            },
            dataType: 'json'
        }); 
  });
</script>