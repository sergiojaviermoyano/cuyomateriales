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
      <label style="margin-top: 7px;">Descripción <strong style="color: #dd4b39">*</strong>: </label>
    </div>
	<div class="col-xs-5">
      <input type="text" class="form-control" id="rubDescripcion" value="<?php echo $data['rubro']['rubDescripcion'];?>" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>  >
    </div>
</div><br>

<div class="row">
    <div class="col-xs-4">
        <label style="margin-top: 7px;">Estado: </label>
    </div>
    <div class="col-xs-5">
        <select class="form-control" id="rubEstado"  <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?> >
          <?php 
              echo '<option value="AC" '.($data['rubro']['rubEstado'] == 'AC' ? 'selected' : '').'>Activo</option>';
              echo '<option value="IN" '.($data['rubro']['rubEstado'] == 'IN' ? 'selected' : '').'>Inactivo</option>';
          ?>
        </select>
    </div>
  </div>

</div>