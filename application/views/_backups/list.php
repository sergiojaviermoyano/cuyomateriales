<input type="hidden" id="permission" value="<?php echo $permission;?>">
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">BackUp</h3>
          <?php
          if (strpos($permission,'Add') !== false) {
            echo '<button class="btn btn-block btn-success" style="width: 100px; margin-top: 10px;" data-toggle="modal" onclick="GenerarBackup()" id="btnAdd" >Generar</button>';
          }
          ?>
        </div><!-- /.box-header -->
        <div class="box-body">
          <div class="alert alert-danger alert-dismissible">
            <h4><i class="icon fa fa-fw fa-database"></i> ¡Importante!</h4>
            Esto permitira generar un copia de seguridad de sus datos. Por favor guarde en un lugar seguro el archivo generado.
          </div>

          <br>

          <a class="btn btn-primary" href="assets/backs/db-backup-last.gz" download style="display: none" id="btn_back">
            <i class="fa fa-fw fa-download"></i>
            Descargar Copia de Seguridad
          </a>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div><!-- /.col -->
  </div><!-- /.row -->
</section><!-- /.content -->

<script>

function GenerarBackup(){
    $('#btn_back').hide();
    WaitingOpen('Generado BackUp');
    var data_ajax={
                    type: 'POST',
                    url: "index.php/backup/generate",
                    data: null,
                    success: function( data ) {
                              WaitingClose();
                              $('#btn_back').show('slow');
                            },
                    error: function(){
                              WaitingClose();
                              //alert("Error al generar el backup.");
                              $('#btn_back').show('slow');
                            },
                    dataType: 'json'
                  };
    $.ajax(data_ajax);
}
</script>