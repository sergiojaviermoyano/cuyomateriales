<div class="row">
	<div class="col-xs-12">
		<div class="alert alert-danger alert-dismissable" id="error" style="display: none">
	        <h4><i class="icon fa fa-ban"></i> Error!</h4>
	        Revise que todos los campos esten completos
      </div>
	</div>
</div>
<h3>
<?php if($data['cajaId'] == -1){
  echo 'Apertura de Caja';
} else {
  echo 'Cierre de Caja';
}
?>
</h3><hr>
<input type="hidden" id="cajaId" value="<?php echo $data['cajaId'];?>">
<div class="row">
	<div class="col-xs-4">
      <label style="margin-top: 7px;">Fondo Inicial <strong style="color: #dd4b39">*</strong>: </label>
    </div>
	<div class="col-xs-5">
      <input type="text" class="form-control" id="cajaImpApertura" value="<?php echo $data['caja']['cajaImpApertura'];?>" <?php echo ($data['cajaId'] != '-1' ? 'disabled="disabled"' : '');?>  >
    </div>
</div><br>

<div class="row">
	<div class="col-xs-4">
      <label style="margin-top: 7px;">Ventas <strong style="color: #dd4b39">*</strong>: </label>
    </div>
	<div class="col-xs-5">
      <input type="text" class="form-control" id="cajaImpVentas" value="<?php echo $data['caja']['cajaImpVentas'];?>" disabled="disabled" >
    </div>
</div><br>

<?php 
if(isset($data['caja']['medios'] ))
{
?>
<div class="row">
  <div class="col-xs-4">
    <label style="margin-top: 7px;">Detalle: </label>
  </div>
  <div class="col-xs-5">
      <?php
      foreach ($data['caja']['medios'] as $item) {
        echo $item['medDescripcion'].' : <b>$'.$item['importe'].'</b><hr>';
      }
      ?>
    </div>
</div><br>
<?php
}
?>

<div class="row">
	<div class="col-xs-4">
      <label style="margin-top: 7px;">Redición <strong style="color: #dd4b39">*</strong>: </label>
    </div>
	<div class="col-xs-5">
      <input type="text" class="form-control" id="cajaImpRendicion" value="<?php echo $data['caja']['cajaImpRendicion'];?>" <?php echo ($data['cajaId'] == '-1' ? 'disabled="disabled"' : '');?>  >
    </div>
</div><br>

<div class="row">
	<div class="col-xs-4">
      <label style="margin-top: 7px;">Apertura <strong style="color: #dd4b39">*</strong>: </label>
    </div>
	<div class="col-xs-5">
      <input type="text" class="form-control" id="cajaApertura" value="<?php echo $data['caja']['cajaApertura'] != null ? date("d-m-Y H:i", strtotime($data['caja']['cajaApertura'])) : ''; ?>" disabled="disabled" >
    </div>
</div><br>

<div class="row">
	<div class="col-xs-4">
      <label style="margin-top: 7px;">Cierre <strong style="color: #dd4b39">*</strong>: </label>
    </div>
	<div class="col-xs-5">
      <input type="text" class="form-control" id="cajaCierre" value="<?php echo $data['caja']['cajaCierre'] != null ? date("d-m-Y H:i", strtotime($data['caja']['cajaCierre'])) : '';?>" disabled="disabled" >
    </div>
</div><br>

<div class="row">
	<div class="col-xs-4">
      <label style="margin-top: 7px;">Usuario <strong style="color: #dd4b39">*</strong>: </label>
    </div>
	<div class="col-xs-5">
      <input type="text" class="form-control" id="usr" value="<?php echo $data['user']['usrLastName'].', '.$data['user']['usrName'] ;?>" disabled="disabled" >
    </div>
</div>
<hr>
<div class="row">
  <div class="col-xs-4"></div>
  <div class="col-xs-5" style="text-align: right">
      <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
      <button type="button" class="btn btn-primary" id="btnSaveBox">Aceptar</button>
    </div>
</div><br>

</div>
<script>
$("#cajaImpApertura").maskMoney({allowNegative: false, thousands:'', decimal:'.'});
$("#cajaImpRendicion").maskMoney({allowNegative: false, thousands:'', decimal:'.'});

$('#btnSaveBox').click(function(){
    
    $('#error').fadeOut('slow');
    WaitingOpen('Guardando cambios');
      $.ajax({
            type: 'POST',
            data: { 
                    id:  $('#cajaId').val(),
                    ape: $('#cajaImpApertura').val() == '' ? 0 : $('#cajaImpApertura').val(),
                    ven: $('#cajaImpVentas').val() == '' ? 0 : $('#cajaImpVentas').val(),
                    cie: $('#cajaImpRendicion').val() == '' ? 0 : $('#cajaImpRendicion').val()
                  },
        url: 'index.php/box/setBox', 
        success: function(result){
                      WaitingClose();
                      load(3);
              },
        error: function(result){
              WaitingClose();
              alert("error");
            },
            dataType: 'json'
        });
  });
</script>