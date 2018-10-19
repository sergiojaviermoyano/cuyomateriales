
<input type="hidden" id="permission" value="<?php echo $permission; ?>">
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Clientes</h3>
          <?php
            if (strpos($permission,'Add') !== false) {
              echo '<button class="btn btn-block btn-success" style="width: 100px; margin-top: 10px;" data-toggle="modal" id="btnAdd">Agregar</button>';
            }
          ?>
        </div><!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-xs-4">
                <label style="margin-top: 7px;">Cliente <strong style="color: #dd4b39">*</strong>: </label>
              </div>
            <div class="col-xs-5">
              <select class="form-control select2" id="cliId" style="width: 100%;" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?> >
                <?php 
                  echo '<option value="-1" selected></option>';
                  foreach ($list as $p) {
                    echo '<option value="'.$p['cliId'].'">'.$p['cliApellido'].', '.$p['cliNombre'].' ('.$p['cliDocumento'].')</option>'; 
                  }
                ?>
              </select>
              </div>
          </div>
          <br>
          <div class="row">
            <div class="col-xs-12">
              <div class="box-header">
              <h4 class="box-title">Estado de Cuenta Corriente</h4>
              </div>
              <div id="ctacteBody">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- Modal -->
<div class="modal fade" id="modalReception" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="width: 80%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span id="modalAction"> </span> Recepción</h4>
      </div>
      <div class="modal-body" id="modalBodyReception">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnSave">Guardar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->

<!-- Modal -->
<div class="modal fade" id="modalDetail" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="width: 80%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body" id="modalBodyDetail">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->

<script>
$(".select2").select2();
$("#cliId").change(function(){
  CargarMoviemientos();
});
function CargarMoviemientos(){
  //prvId_ = $("#prvId").val();
  WaitingOpen('Cargando Movimientos');
      $.ajax({
            type: 'POST',
            data: { 
                    cliId : $("#cliId").val()
                  },
        url: 'index.php/cuentacorriente/getCtaCteC', 
        success: function(result){
                      WaitingClose();
                      $("#ctacteBody").html(result.html);
              },
        error: function(result){
              WaitingClose();
              alert("error");
            },
            dataType: 'json'
        });
}
LoadIconAction('modalAction','Add');
$('#btnAdd').click(function(){
  if($('#cliId').val() != '-1'){
    LoadIconAction('modalAction__','Add');
    WaitingOpen('Espere...');
    $.ajax({
            type: 'POST',
            data: null,
            url: 'index.php/box/isOpenBox',
            success: function(result){
                        WaitingClose();
                        if(result == 0){
                        } else {
                          $("#cctepImporte").maskMoney({allowNegative: true, thousands:'', decimal:'.'});
                          $("#cctepImporte").val('');
                          $('#cctepConcepto').val('');
                          $('#modalMove').modal('show');
                          var data = $('#cliId').select2('data');
                          if(data) {
                            $('#prvName').html('<label>' + data[0].text + '</label>');
                          }
                        }
                },
            error: function(result){
                WaitingClose();
                ProcesarError(result.responseText, 'noModal');
              },
              dataType: 'json'
          });
  }
});
$('#btnSave22').click(function(){
  setTimeout("$('#error').hide('slow');",2000);
  if($('#cctepConcepto').val() == ''){
    $('#error').show('slow');
    $('#errorMsj').html('Es necesario agregar un concepto.');
    return;
  }
  if($('#cctepImporte').val() == ''){
    $('#error').show('slow');
    $('#errorMsj').html('Es necesario indicar un importe.');
    return;
  }
  WaitingOpen('Cargando Movimiento');
      $.ajax({
            type: 'POST',
            data: { 
                    cliId:  $("#cliId").val(),
                    cpto:   $('#cctepConcepto').val(),
                    impte:  $('#cctepImporte').val()
                  },
        url: 'index.php/cuentacorriente/setCtaCteC', 
        success: function(result){
                      $('#modalMove').modal('hide');
                      CargarMoviemientos();
              },
        error: function(result){
              WaitingClose();
              ProcesarError(result.responseText, 'modalMove');
            },
            dataType: 'json'
        });
});
function LoadRec(id_,action){
  LoadIconAction('modalActionX','View');
  WaitingOpen('Cargando Recepción');
  $.ajax({
    type: 'GET',
    data: { },
    url: 'index.php/sale/mayoristaGet/'+id_, 
    success: function(result){
      console.debug("result: %o",result);
      WaitingClose();
      $("#modalDetail #modalBodyDetail").html(result);
      $("#modalDetail #modalBodyDetail").find("#btnServiceEfectivo").hide();
      $("#modalDetail #modalBodyDetail").find("#btnServiceBuy").hide();
      $("#modalDetail #modalBodyDetail").find("#closex").hide();
      $("#modalDetail #modalBodyDetail").find("h3.box-title strong").html("Detalle de Venta");
      //$(".select2").select2();
      //$('#recFecha').datepicker({maxDate: '0'});
      //$("#tcImporte").maskMoney({allowNegative: false, thousands:'', decimal:'.'});
      setTimeout("$('#modalDetail').modal('show')",800);
    },
    error: function(result){
          WaitingClose();
          alert("error");
        },
        dataType: 'json'
    });
}

  function Print(id__){
    WaitingOpen('Generando reporte...');
    LoadIconAction('modalAction__p','Print');
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

  function PrintRecibo(id__){
    WaitingOpen('Generando reporte...');
    LoadIconAction('modalAction__p','Print');
    $.ajax({
            type: 'POST',
            data: {
                    id : id__
                  },
        url: 'index.php/cuentacorriente/printRecibo',
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
  
  function printExtracto(){
    WaitingOpen('Generando reporte...');
    LoadIconAction('modalAction__p','Print');
    $.ajax({
            type: 'POST',
            data: {
                    cliId : $("#cliId").val()
                  },
        url: 'index.php/cuentacorriente/printExtracto',
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
<div class="modal fade" id="modalMove" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span id="modalAction__"> </span> Movimiento</h4> 
      </div>
      <div class="modal-body" id="modalBodyMove">
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="alert alert-danger alert-dismissable" id="error" style="display: none">
                    <h4><i class="icon fa fa-ban"></i> Error!</h4>
                    <label id="errorMsj">Error!!!</label>
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-4">Cliente:</div>
            <div class="col-xs-8" id="prvName"></div>
          </div><br>
          <div class="row">
            <div class="col-xs-4" style="margin-top: 7px;">Concepto:</div>
            <div class="col-xs-8">
              <input type="text" class="form-control" maxlength="50" id="cctepConcepto">
            </div>
          </div><br>
          <div class="row">
            <div class="col-xs-4" style="margin-top: 7px;">Importe:</div>
            <div class="col-xs-6">
              <input type="text" class="form-control" id="cctepImporte">
            </div>
            <div class="col-xs-2" style="margin-top: 7px;"><label>(*)</label></div>
          </div><br><br>
          <div class="row">
            <div class="col-xs-12"><i>* Los valores ingresados en negativo(-) se imputan como deuda para el <strong>Cliente</strong><br>
              * Los valores en positivo(+) se imputan como un pago por el <strong>Cliente</strong>.</i></div>
          </div>
        </section>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnSave22">Guardar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<!--
<div class="modal fade" id="modalReception" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="width: 80%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span id="modalActionX"> </span> Recepción</h4> 
      </div>
      <div class="modal-body" id="modalBodyReception">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>
-->
<!-- Modal -->
<div class="modal fade" id="modalPrint" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="width: 50%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel__"><span id="modalAction__p"> </span> Comprobante</h4>
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