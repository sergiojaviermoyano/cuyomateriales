<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Reporte de Ingresos</h3>
          <hr>
        </div><!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-xs-1">
                <label style="margin-top: 7px;">Desde <strong style="color: #dd4b39">*</strong>: </label>
            </div>
            <div class="col-xs-2">
                <input type="text" class="form-control" placeholder="dd/mm/aaaa" id="dtDesde" readonly="">
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-xs-1">
                <label style="margin-top: 7px;">Hasta <strong style="color: #dd4b39">*</strong>: </label>
            </div>
            <div class="col-xs-2">
                <input type="text" class="form-control" placeholder="dd/mm/aaaa" id="dtHasta" readonly="">
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-xs-1">
                
            </div>
            <div class="col-xs-1">
                <button type="button" class="btn btn-primary" id="btnSave">Consultar</button>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-5" style="min-height: 100px;">
                <label>Total Ingresos</label>
                <h2 id="total">0.00</h2>
                <a href="assets/reports/reporteDeIngresos.pdf" id="linkDownload" download style="display: none;">Descargar en formato PDF<i class="fa fa-fw fa-file-pdf-o" style="color: red"></i></a>
            </div>
          </div>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div><!-- /.col -->
  </div><!-- /.row -->
</section><!-- /.content -->

<script>
  
  $('#dtDesde').datepicker({maxDate: '0'});
  $('#dtHasta').datepicker({maxDate: '0'});
  
  $('#btnSave').click(function(){
  	
  	if($('#dtDesde').val() == '')
  	{
  		return;
  	}

    if($('#dtHasta').val() == '')
    {
      return;
    }

    WaitingOpen('Calculando Ingresos');
    	$.ajax({
          	type: 'POST',
          	data: { 
                    from :  $('#dtDesde').val(), 
                    to:     $('#dtHasta').val()
                  },
    		url: 'index.php/report/getIn', 
    		success: function(result){
                			WaitingClose();
                      if(result.crdHaber != null){
                			 $('#total').html(result.crdHaber);
                      }else{
                        $('#total').html('0.00');
                      }
                      $('#linkDownload').show();
    					},
    		error: function(result){
    					WaitingClose();
    					alert("error");
              $('#total').html('0.00');
    				},
          	dataType: 'json'
    		});
  });

</script>