<input type="hidden" id="permission" value="<?php echo $permission;?>">
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Artículos Entregados</h3>
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
            url: 'index.php/sale/getArticles', 
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