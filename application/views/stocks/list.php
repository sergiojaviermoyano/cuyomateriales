<input type="hidden" id="permission" value="<?php echo $permission;?>">
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Stock</h3>
          <?php
          if (strpos($permission,'Add') !== false) {
              echo '<button class="btn btn-block btn-success" style="width: 100px; margin-top: 10px;" data-toggle="modal" id="btnAdd" title="Nuevo">Ajuste</button>';
            }
          ?>
        </div><!-- /.box-header -->        
        <div class="box-body">
        <?php if (strpos($permission,'View') !== false) 
          { 
        ?>
        <hr>
        <div class="row">
          <div class="col-xs-12">
            <div class="alert alert-danger alert-dismissable" id="error" style="display: none">
                  <h4><i class="icon fa fa-ban"></i> Error!</h4>
                  Revise que todos los campos esten completos
              </div>
          </div>
        </div>

        <div class="row">
          <div class="col-xs-1">
            <label style="margin-top: 7px;">Artículo: </label></td>
          </div>
          <div class="col-xs-6">
            <select class="form-control select2" id="artiId" style="width: 100%;">
              <?php 
                  echo '<option value="-1" selected></option>';
                foreach ($articles as $a) {
                  echo '<option value="'.$a['artId'].'">'.$a['artDescription'].' ('.$a['artBarCode'].')</option>';
                }
              ?>
            </select>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-xs-1">
            <label style="margin-top: 7px;">Desde: </label>
          </div>
          <div class="col-xs-2">
            <input type="text" class="form-control" placeholder="dd-mm-aaaa" id="fitFrom" value="" readonly="" style="width: 110px;">
          </div>
          <div class="col-xs-1">
            <label style="margin-top: 7px;">Hasta: </label>
          </div>
          <div class="col-xs-2">
            <input type="text" class="form-control" placeholder="dd-mm-aaaa" id="fitTo" value="" readonly="" style="width: 110px;">
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-xs-2">
            <button type="button" class="btn btn-primary" id="btnView">Consultar</button>
          </div>
        </div>
        <hr>
        <?php 
          }
        ?>

        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div><!-- /.col -->
  </div><!-- /.row -->
</section><!-- /.content -->

<script>  
  $('#fitFrom').datepicker({maxDate: '0'});
  $('#fitTo').datepicker({maxDate: '0'});
  $(".select2").select2();
    
  $('#btnAdd').click(function(){
  	LoadIconAction('modalActionFit','Add');
  	WaitingOpen('Cargando Ajuste');
    $.ajax({
            type: 'POST',
            data: null  ,
            url: 'index.php/stock/getFitByArtId', 
            success: function(result){
                          WaitingClose();
                          $("#modalBodyFit").html(result.html);
                          $(".select2").select2();
                          setTimeout("$('#modalFit').modal('show')",800);
                  },
            error: function(result){
                  WaitingClose();
                  alert("error");
                },
                dataType: 'json'
            });
  });

  
  $('#btnView').click(function(){

  	var hayError = false;
    if($('#artiId').val() == '' || $('#artiId').val() == '-1')
    {
      hayError = true;
    }

    if($('#fitFrom').val() == '' || $('#fitTo').val() == '')
    {
      hayError = true;
    }
    
    if(hayError == true){
    	$('#error').fadeIn('slow');
      setTimeout("$('#error').fadeOut('slow');",2000);
    	return;
    }

    $('#error').fadeOut('slow');

    LoadIconAction('modalAction','View');
    WaitingOpen('Consultando...');
    	$.ajax({
          	type: 'POST',
          	data: { 
                    artId : $('#artiId').val(), 
                    dtFrm: $('#fitFrom').val(), 
                    dtTo: $('#fitTo').val()
                  },
    		url: 'index.php/stock/getStockByArtId', 
    		success: function(result){
                			WaitingClose();
                      $("#modalBodyStock").html(result.html);
                			setTimeout("$('#modalStock').modal('show')",800);
    					},
    		error: function(result){
    					WaitingClose();
    					alert("error");
    				},
          	dataType: 'json'
    		});
    
  });

  $('#btnSave').click(function(){
    var hayError = false;
    if($('#artId_').val() == '' || $('#artId_').val() == '-1')
    {
      hayError = true;
    }

    if($('#fitCant').val() == '' || parseInt($('#fitCant').val()) == 0)
    {
      hayError = true;
    }
    
    if(hayError == true){
      $('#error_').fadeIn('slow');
      setTimeout("$('#error_').fadeOut('slow');",2000);
      return;
    }

    $('#error_').fadeOut('slow');

    LoadIconAction('modalAction','View');
    WaitingOpen('Ajustando...');
      $.ajax({
            type: 'POST',
            data: { 
                    artId: $('#artId_').val(), 
                    cant : $('#fitCant').val()
                  },
        url: 'index.php/stock/setFit', 
        success: function(result){
                      WaitingClose();
                      $('#modalFit').modal('hide');
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
<div class="modal fade" id="modalStock" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="width: 50%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span id="modalAction"> </span> Movimientos de Stock</h4> 
      </div>
      <div class="modal-body" id="modalBodyStock">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
        <!--<button type="button" class="btn btn-primary" id="btnSave">Guardar</button>-->
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalFit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="width: 50%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span id="modalActionFit"> </span> Ajuste de Stock</h4> 
      </div>
      <div class="modal-body" id="modalBodyFit">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btnSave">Guardar</button>
      </div>
    </div>
  </div>
</div>