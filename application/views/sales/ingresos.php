<input type="hidden" id="permission" value="<?php echo $permission;?>">
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Detalle Ingresos</h3>
        </div><!-- /.box-header -->        
        <div class="box-body">
        <?php if (strpos($permission,'View') !== false) 
          { 
        ?>
        <hr>

        <div class="row">
          <div class="col-xs-1">
            <label style="margin-top: 7px;">Fecha: </label>
          </div>
          <div class="col-xs-2">
            <input type="text" class="form-control" placeholder="dd-mm-aaaa" id="fitDay" value="" readonly="" style="width: 110px;">
          </div>
          <!--
          <div class="col-xs-1">
            <label style="margin-top: 7px;">Hasta: </label>
          </div>
          <div class="col-xs-2">
            <input type="text" class="form-control" placeholder="dd-mm-aaaa" id="fitTo" value="" readonly="" style="width: 110px;">
          </div>
          -->
          <div class="col-xs-2">
            <button type="button" class="btn btn-primary" id="btnView">Consultar</button>
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-xs-12" id="detailSaless">

          </div>
        </div>
        <?php 
          }
        ?>

        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div><!-- /.col -->
  </div><!-- /.row -->
</section><!-- /.content -->

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

<script>  
  $('#fitDay').datepicker({maxDate: '0'});

  $('#btnView').click(function(){
      if($('#fitDay').val() == ''){
        return;
      } else {
        WaitingOpen('Consultando...');
          $.ajax({
                type: 'POST',
                data: { 
                        day : $('#fitDay').val()
                      },
            url: 'index.php/sale/getSales__', 
            success: function(result){
                          $("#detailSaless").html(result.html);
                          setTimeout("WaitingClose();",800);
                  },
            error: function(result){
                  WaitingClose();
                  alert("error");
                },
                dataType: 'json'
            });
      }
  });
</script>