<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Reporte de Caja </h3>
        </div><!-- /.box-header -->        
        <div class="box-body">
        
          <div class="row">
            <div class="col-xs-1">
              <label style="margin-top: 7px;">Fecha: </label>
            </div>
            <div class="col-xs-2">
              <input type="text" class="form-control" placeholder="dd-mm-aaaa" id="fitFrom" value="" readonly="" style="width: 110px;">
            </div>
          </div>
          <br>
          <div class="row">
              <div class="col-xs-12">
                  <iframe style="width: 100%; height: 600px;" id="printBox" src=""></iframe>
              </div>
          </div>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div><!-- /.col -->
  </div><!-- /.row -->
</section><!-- /.content -->
<script>
  $('#fitFrom').datepicker({maxDate: '0'});
  $('#fitFrom').datepicker().datepicker("setDate", new Date());
  $("#fitFrom").datepicker({
    onSelect: function(dateText) {
      $(this).change();
    }
  }).on("change", function() {
    ImprimirCaja();
  });

  ImprimirCaja();

  function ImprimirCaja(){
    WaitingOpen('Generando reporte...');
    $.ajax({
            type: 'POST',
            data: {
                    date : $('#fitFrom').val()
                  },
        url: 'index.php/sale/printBox',
        success: function(result){
                      WaitingClose();
                      var url = "./assets/reports/box/caja.pdf";
                      $('#printBox').attr('src', url);
              },
        error: function(result){
              WaitingClose();
              ProcesarError(result.responseText, '--');
            },
            dataType: 'json'
        });
  }
</script>