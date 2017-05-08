<div class="row">
	<div class="col-xs-12">
		<div class="alert alert-danger alert-dismissable" id="error" style="display: none">
	        <h4><i class="icon fa fa-ban"></i> Error!</h4>
	        Revise que todos los campos esten completos
      </div>
	</div>
</div>
<?php //var_dum($data['wood']);?>
<div class="row">
	<div class="col-xs-4">
      <label style="margin-top: 7px;">Descripción <strong style="color: #dd4b39">*</strong>: </label>
    </div>
	<div class="col-xs-5">
      <input type="text" class="form-control" placeholder="Roble rojo" id="madDescripcion" value="<?php echo $data['wood']['madDescripcion'];?>" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>  >
    </div>
</div><br>
<div class="row">
	<div class="col-xs-4">
      <label style="margin-top: 7px;">Precio x Pie<strong style="color: #dd4b39">*</strong>: </label>
    </div>
	<div class="col-xs-5">
      <input type="text" class="form-control" placeholder="" id="madPrecio" value="<?php echo $data['wood']['madPrecio'];?>" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>  >
    </div>
</div><br>
<div class="row">
	<div class="col-xs-4">
      <label style="margin-top: 7px;">Precio x Pulgada<strong style="color: #dd4b39">*</strong>: </label>
    </div>
	<div class="col-xs-5">
      <input type="text" class="form-control" placeholder="" id="madPrecioPulgada" value="<?php echo $data['wood']['madPrecioPulgada'];?>" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>  >
    </div>
</div><br>
<div class="row">
	<div class="col-xs-4">
      <label style="margin-top: 7px;">Estado <strong style="color: #dd4b39">*</strong>: </label>
    </div>
	<div class="col-xs-5">
	    <select class="form-control select2" id="madEstado" style="width: 100%;" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?> >
	      <option value="AC" <?php echo ($data['wood']['madEstado'] == 'AC' ? 'selected' : '');?> >Activo</option>';
	      <option value="IN" <?php echo ($data['wood']['madEstado'] == 'IN' ? 'selected' : '');?> >Inactivo</option>';
    	</select>
    </div>
</div><br>
</div>

<script>
$('#madPrecio').keyup(function(){
	//Calcular precio por pulgada
	var precioPie = parseFloat($('#madPrecio').val());
	var precioPulgada = 0;
	if(precioPie > 0){
		precioPulgada = parseFloat(precioPie / 3.77).toFixed(3);
		$('#madPrecioPulgada').val(precioPulgada);
	} else {
		$('#madPrecioPulgada').val('');
	}
});

$('#madPrecioPulgada').keyup(function(){
	//Calcular precio por pulgada
	var precioPulgada = parseFloat($('#madPrecioPulgada').val());
	var precioPie = 0;
	if(precioPulgada > 0){
		precioPie = parseFloat(precioPulgada * 3.77).toFixed(3);
		$('#madPrecio').val(precioPie);
	} else {
		$('#madPrecio').val('');
	}
});
</script>