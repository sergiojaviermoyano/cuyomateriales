<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
          <?php
          if ($data['cajaId'] == -1) {
            ?>
            <div class="box-header">
              <h3>No hay cajas abiertas para poder cobrar </h3>
            </div>
            <?php
          } else {
          ?>
            <div class="box-body">
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                      <li class="tab-pane active"><a href="#tab_1_1" data-toggle="tab">Ordenes de Compra</a></li>
                      <li><a href="#tab_1_2" data-toggle="tab">Presupuestos</a></li>
                </ul>
                <div class="tab-content no-padding">
                
                  <div class="chart tab-pane active" id="tab_1_1">
                    <table id="order_table" class="table table-bordered table-hover">
                      <thead>
                        <tr>
                          <th width="20%">Acciones</th>
                          <th>Observación</th>
                          <th>Fecha</th>
                          <th>Estado</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>

                  <div class="chart tab-pane" id="tab_1_2">
                    <table id="presu_table" class="table table-bordered table-hover">
                      <thead>
                        <tr>
                          <th width="20%">Acciones</th>
                          <th>Observación</th>
                          <th>Fecha</th>
                          <th>Estado</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>
                  </div>
                </div>
              </div>
            </div><!-- /.box-body -->
          <?php
          }
          ?>
          <input type="hidden" id="validez" value="<?php echo $data['validez']['validezpresupuesto'];?>">
      </div><!-- /.box -->
    </div><!-- /.col -->
  </div><!-- /.row -->
</section><!-- /.content -->

<!-- Modal Cobranza OC-->
<div class="modal fade" id="modalCobrar" tabindex="2000" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="width: 80%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="cerro()"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span> <i class="fa fa-fw fa-money" style="color: #00a65a;"></i> </span> Cobrar</h4> 
      </div>
      <div class="modal-body" id="modalBodyCobrar">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnCobrar">Aceptar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Medios de Pago -->
<div class="modal fade" id="modalMedios" tabindex="3000" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="width: 50%">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><span> <i class="fa fa-fw fa-money" style="color: #00a65a;"> </i> </span> Medios de Pago</h4> 
      </div>
      <div class="modal-body" id="modalBodyMedios">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnPago">Aceptar</button>
      </div>
    </div>
  </div>
</div>

<script>
var gocId;
function Factuar(ocId){
  gocId = ocId;
  WaitingOpen('Cargando orden...');
    $.ajax({
            type: 'POST',
            data: { 
                    id : ocId
                  },
        url: 'index.php/sale/getOrden', 
        success: function(result){
                      WaitingClose();
                      $('#modalBodyCobrar').html(result.html);
                      setTimeout("$('#modalCobrar').modal('show');",800);
              },
        error: function(result){
              WaitingClose();
              ProcesarError(result.responseText, 'modalCobrar');
            },
            dataType: 'json'
        });
  
}
var pagos = [];
$('#btnCobrar').click(function(){
  pagos = [];
  WaitingOpen('Cargando medios...');
      $.ajax({
            type: 'POST',
            data: { 
                    id : gocId,
                    to : $('#saleTotal').html()
                  },
        url: 'index.php/sale/getMPagos', 
        success: function(result){
                      WaitingClose();
                      $('#modalBodyMedios').html(result.html);
                      setTimeout("$('#modalMedios').modal('show');",800);
              },
        error: function(result){
              WaitingClose();
              ProcesarError(result.responseText, 'modalMedios');
            },
            dataType: 'json'
        });
});

function addItem(medId, tmpId, tipo){
  //Buscar si ya esta el tmpId en el array y eliminar
  pagos = pagos.filter(function( obj ) {
    return obj.tmp !== tmpId;
  });
  
  if(medId == -1){
    //Medio multiple (tarjeta)
    if($('#'+tmpId+'_medId').val() == -1 || $('#'+tmpId+'_importe').val() == ''){
      alert('Completa el valor');
    }else{
      var object = {
        mId:      $('#'+tmpId+'_medId').val(),
        imp:      $('#'+tmpId+'_importe').val(),
        tmp:      tmpId,
        de1:      $('#'+tmpId+'_des1').val(),
        de2:      $('#'+tmpId+'_des2').val(),
        de3:      $('#'+tmpId+'_des3').val(),
      };
      pagos.push(object);
      var div = '#'+tmpId+'_load';
      $(div).hide();
    }
  } else {
    //Medio simple (efectivo / cta cte / etc)
    if($('#'+medId+'_importe').val() == ''){
      alert('Completa el valor');
    }else{
      var object = {
        mId:      medId,
        imp:      $('#'+medId+'_importe').val(),
        tmp:      tmpId,
        de1:      null,
        de2:      null,
        de3:      null
      };
      pagos.push(object);
      var div = '#'+tmpId+'_load';
      $(div).hide();
    }
  }
  CargarImportes();
}

function CargarImportes(){
  var pag = 0;
  $.each(pagos, function(index, result){
      $('#'+result.tmp+'_total').html(parseFloat(result.imp).toFixed(2));
      pag += parseFloat(result.imp);
  });

  $('#pagos_suma').html(parseFloat(pag).toFixed(2));
}

$('#btnPago').click(function(){
  var importeAPagar = parseFloat($('#saleTotal').html().replace(',',''));
  var importePagado = parseFloat($('#pagos_suma').html());

  if(importeAPagar == importePagado){
    WaitingOpen('Cobrando...');
    $.ajax({
          type: 'POST',
          data: { 
                  id : gocId,
                  pa : pagos
                },
      url: 'index.php/sale/setSale', 
      success: function(result){
                    WaitingClose();
                    $('#modalMedios').modal('hide');
                    $('#modalCobrar').modal('hide');
                    load(1);
            },
      error: function(result){
            WaitingClose();
            ProcesarError(result.responseText, 'modalMedios');
          },
          dataType: 'json'
      }); 
  }else{
    alert('el pago no es igual');
  }
});

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
          "url":'index.php/order/listingOrdersSales',
          "dataSrc": function (json) {
            var output=[];
            var permission=$("#permission").val();
            permission= permission.split('-');
            $.each(json.data,function(index,item){
              var td_1="";
                  td_1+='<i class="fa fa-fw fa-money" style="color: #00a65a; cursor: pointer; margin-left: 15px;" onclick="Factuar('+item.ocId+')"></i>';
                  td_1+='<i class="fa fa-fw fa-print" style="color: #A4A4A4; cursor: pointer; margin-left: 15px;" onclick="Print('+item.ocId+')"></i>';

              var td_2= item.ocObservacion;
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
                default:{
                  td_4='';
                  break;
                }
              }
              //for(i=0;i<=100000;i++){
                output.push([td_1,td_2,td_3,td_4]);

              //}

            });
            return output;
          },
          error:function(the_error){
            console.debug(the_error);
          }
        }

    });

var dataTable2= $('#presu_table').DataTable({
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
          "url":'index.php/order/listingOrdersPresu',
          "dataSrc": function (json) {
            var output=[];
            var permission=$("#permission").val();
            permission= permission.split('-');
            var validez = parseInt($('#validez').val());
            $.each(json.data,function(index,item){
              var td_1="";
                  td_1+='<i class="fa fa-fw fa-money" style="color: #00a65a; cursor: pointer; margin-left: 15px;" onclick="Factuar('+item.ocId+')"></i>';
                  td_1+='<i class="fa fa-fw fa-print" style="color: #A4A4A4; cursor: pointer; margin-left: 15px;" onclick="Print('+item.ocId+')"></i>';

              var td_2="";
                  var date = new Date(item.ocFecha);
                  if(vencimiento(date) > validez){
                    td_2+= '<small class="label pull-left bg-orange" style="font-size: 10px; margin-right: 9px;">Vencido</small>  &nbsp; &nbsp; ';
                  }
                 if(item.ocEsPresupuesto==1){
                   td_2+= '<small class="label pull-left bg-navy" style="font-size: 14px; margin-right: 5px;" title="Presupuesto">P</small>  &nbsp; &nbsp; ';
                 }
                 td_2+= item.ocObservacion;//item.ocObservacion;
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
        }

    });
  });

function vencimiento(date){
  var now = new Date();
  return daydiff(date, now);
}

/*
function parseDate(str) {
    var mdy = str.split('-');
    return new Date(mdy[2], mdy[0]-1, mdy[1]);
}
*/

function daydiff(first, second) {
    return Math.round((second-first)/(1000*60*60*24));
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
</script>