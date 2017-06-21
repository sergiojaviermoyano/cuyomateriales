<section class="content" >
<div class="box" style="padding-left: 30px;">
  <div class="box-header">
    <h3 class="box-title">Sistema</h3>
  </div>
  <div class="row">
    <div class="col-xs-12">
      <div class="alert alert-danger alert-dismissable" id="error" style="display: none">
            <h4><i class="icon fa fa-ban"></i> Error!</h4>
            Revise que todos los campos esten completos
        </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-4">
        <label style="margin-top: 7px;">Título 1: </label>
      </div>
    <div class="col-xs-5">
        <input type="text" class="form-control" id="title1" value="<?php echo $data['conf']['title1'];?>"  >
      </div>
  </div><br>

  <div class="row">
    <div class="col-xs-4">
        <label style="margin-top: 7px;">Título 2: </label>
      </div>
    <div class="col-xs-5">
        <input type="text" class="form-control" id="title2" value="<?php echo $data['conf']['title2'];?>"  >
      </div>
  </div><br>

  <div class="row">
    <div class="col-xs-4">
        <label style="margin-top: 7px;">Utiliza Orden de Compra: </label>
      </div>
    <div class="col-xs-5">
        <input type="checkbox" id="utilizaordendecompra" style="margin-top:10px;" <?php echo($data['conf']['utilizaordendecompra'] == true ? 'checked': ''); ?> >
      </div>
  </div><br>

  <div class="row">
    <div class="col-xs-4">
        <label style="margin-top: 7px;">Válidez Presupuesto: </label>
      </div>
    <div class="col-xs-5">
        <input type="number" class="form-control" id="validezpresupuesto" value="<?php echo $data['conf']['validezpresupuesto'];?>"  >
      </div>
  </div><br>

  <div class="row">
    <div class="col-xs-6">
        <button type="submit" id="btnAceptar" class="btn btn-success pull-right"> Aceptar </button>
    </div>
  </div><br>

</div>
</section>
</div>

<script>
$('#btnAceptar').click(function(){

    var hayError = false;
    var error_message="";
    if($('#title1').val() == '')
    {
      hayError = true;
    }

    if(hayError == true){
      $('#error').fadeIn('slow');
      return;
    }

    $('#error').fadeOut('slow');
    WaitingOpen('Guardando cambios');
      $.ajax({
            type: 'POST',
            data: { 
                    title1:   $('#title1').val(),
                    title2:   $('#title2').val(),
                    orcomp:   $('#utilizaordendecompra').prop('checked'),
                    dias:     $('#validezpresupuesto').val()
                  },
        url: 'index.php/configuration/seConfiguration', 
        success: function(result){
                      WaitingClose();
              },
        error: function(result){
              WaitingClose();
              alert(result);
            },
            dataType: 'json'
        });
  });
</script>