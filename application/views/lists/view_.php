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
      <input type="text" class="form-control" id="lpDescripcion" value="<?php echo $data['lista']['lpDescripcion'];?>" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>  >
    </div>
</div><br>

<div class="row">
  <div class="col-xs-4">
      <label style="margin-top: 7px;">Margen %: </label>
    </div>
  <div class="col-xs-5">
      <input type="text" class="form-control" id="lpMargen" value="<?php echo $data['lista']['lpMargen'];?>" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>  >
    </div>
</div><br>

<div class="row">
  <div class="col-xs-4">
      <label style="margin-top: 7px;">Lista x Defecto: </label>
    </div>
  <div class="col-xs-5">
      <input type="checkbox" id="lpDefault" style="margin-top:10px;" <?php echo($data['lista']['lpDefault'] == true ? 'checked': ''); ?> <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?> >
    </div>
</div><br>

<div class="row">
    <div class="col-xs-4">
        <label style="margin-top: 7px;">Estado: </label>
    </div>
    <div class="col-xs-5">
        <select class="form-control" id="lpEstado"  <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?> >
          <?php 
              echo '<option value="AC" '.($data['lista']['lpEstado'] == 'AC' ? 'selected' : '').'>Activo</option>';
              echo '<option value="IN" '.($data['lista']['lpEstado'] == 'IN' ? 'selected' : '').'>Inactivo</option>';
          ?>
        </select>
    </div>
  </div>

</div>