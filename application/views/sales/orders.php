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
              <table id="rubros" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th width="1%">Facturar</th>
                    <th>Observación</th>
                    <th width="15%" style="text-align: center">Fecha</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    foreach($data['ordenes'] as $o)
                    {
                            echo '<tr>';
                            echo '<td>';
                              echo '<i class="fa fa-fw fa-money" style="color: #00a65a; cursor: pointer; margin-left: 15px;" onclick="Factuar('.$o['ocId'].')"></i>';
                            echo '</td>';
                            echo '<td style="text-align: left">'.($o['ocEsPresupuesto'] ? '<small class="label pull-left bg-navy" style="margin-top:4px;">Presupuesto</small>  ' : '').''.$o['ocObservacion'].'</td>';
                            echo '<td style="text-align: center">';
                              echo date("d-m-Y H:i", strtotime($o['ocFecha']));
                            echo '</td>';
                            echo '</tr>';
                    }
                  ?>
                </tbody>
              </table>
            </div><!-- /.box-body -->
          <?php
          }
          ?>
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
</script>