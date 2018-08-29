<section class="content">
  <!--
  <div class="row">
    <div class="col-xs-4">
      <div class="info-box" style="cursor: pointer" onClick="cargarView('sale', 'mayorista', '')">
        <span class="info-box-icon bg-yellow"><i class="fa fa-fw fa-truck"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Agregar</span>
          <span class="info-box-number">Venta Mayorista</span>
        </div>
      </div>
    </div>

    <div class="col-xs-4">
      <div class="info-box" style="cursor: pointer" onClick="cargarView('sale', 'minorista', '')">
        <span class="info-box-icon bg-green"><i class="fa fa-fw fa-cart-plus"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Agregar</span>
          <span class="info-box-number">Venta</span>
        </div>
      </div>
    </div>

    <div class="col-xs-4">
      <div class="info-box" style="cursor: pointer" onClick="cargarView('sale', 'preventa')">
        <span class="info-box-icon bg-red"><i class="fa fa-fw fa-clock-o"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Agregar</span>
          <span class="info-box-number">Plan Reserva</span>
        </div>
      </div>
    </div>

    <div class="col-xs-6">
      <div class="box box-warning direct-chat direct-chat-warning">
        <div class="box-header with-border">
          <h3 class="box-title">Buscador Rápido</h3>
        </div>
        <div class="box-body" style="padding: 15px">
          <div class="row">
            <div class="col-xs-12">
              <input type="text" class="form-control" placeholder="Código" id="artCode" value="" >
            </div>
          </div>
          <div class="row" style="margin-top: 5px;">
            <div class="col-xs-2" style="margin-top: 7px;">
              Artículo:
            </div>
            <div class="col-xs-10">
              <label id="artDescripcion" style="font-size: 22px">--------------</label>
            </div>
          </div>
          <div class="row" style="margin-top: 5px;">
            <div class="col-xs-2" style="margin-top: 7px;">
              Precio:
            </div>
            <div class="col-xs-10">
              <label id="artPrecio" style="font-size: 22px"> $--,-- </label>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  --><!-- /.row -->
  <div class="row">
    <div class="col-xs-12">
      <?php $this->load->view('buscadores/articlespricemain'); ?>
    </div>
  </div>
</section><!-- /.content -->

<script>

setTimeout(function () { $('#txtArtPriceMain').focus();}, 1000);
/*
$('#artCode').keypress(function(e){
    var code = e.which;
      if(code==13){
        BuscarArticulo();
      }
});

function BuscarArticulo(){
  WaitingOpen('Buscando Artículo');
  $.ajax({
        type: 'POST',
        data: {
                code: $('#artCode').val()
              },
        url: 'index.php/article/searchByCode',
        success: function(result){
                      WaitingClose();
                      $('#artCode').val('');
                      if(result == false){
                        $('#artDescripcion').html('<i>Atículo no encontrado.</i>')
                        $('#artPrecio').html('$ --,--');
                      }else {
                        $('#artDescripcion').html(result.artDescription)
                        $('#artPrecio').html('$ ' + parseFloat(result.pVenta).toFixed(2));
                      }

              },
        error: function(result){
              WaitingClose();
              ProcesarError(result.responseText, 'modal');
            },
            dataType: 'json'
        });
}

function printTicket(srvId){
  WaitingOpen('Generando ticket...');
  LoadIconAction('modalAction__','Print');
  $.ajax({
          type: 'POST',
          data: {
                  id : srvId
                },
      url: 'index.php/service/printTicket',
      success: function(result){
                    WaitingClose();
                    var url = "./assets/tickets/" + result;
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

function CobrarService(srvId){
  WaitingOpen('Espere...');
  $.ajax({
          type: 'POST',
          data: null,
      url: 'index.php/box/isOpenBox',
      success: function(result){
                    WaitingClose();
                    if(result == 0){

                    } else {
                      cargarView('sale', 'cobrar', srvId);
                    }
            },
      error: function(result){
            WaitingClose();
            ProcesarError(result.responseText, 'noModal');
          },
          dataType: 'json'
      });
}

function AgregarVenta(){
  WaitingOpen('Espere...');
  $.ajax({
          type: 'POST',
          data: null,
      url: 'index.php/box/isOpenBox',
      success: function(result){
                    WaitingClose();
                    if(result == 0){

                    } else {
                      cargarView('sale', 'add', '');
                    }
            },
      error: function(result){
            WaitingClose();
            ProcesarError(result.responseText, 'noModal');
          },
          dataType: 'json'
      });
}
*/
</script>
