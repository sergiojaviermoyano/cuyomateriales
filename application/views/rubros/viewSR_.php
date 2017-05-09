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
      <label style="margin-top: 7px;">Rubro <strong style="color: #dd4b39">*</strong>: </label>
    </div>
  <div class="col-xs-5">
      <select class="form-control" id="rubId"  <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?> >
        <?php 
          foreach ($data['rubros'] as $r) {
            echo '<option value="'.$r['rubId'].'" '.($data['subrubro']['rubId'] == $r['rubId'] ? 'selected' : '').'>'.$r['rubDescripcion'].'</option>';
          }
        ?>
      </select>
    </div>
</div><br>

<div class="row">
	<div class="col-xs-4">
      <label style="margin-top: 7px;">Descripción <strong style="color: #dd4b39">*</strong>: </label>
    </div>
	<div class="col-xs-5">
      <input type="text" class="form-control" id="subrDescripcion" value="<?php echo $data['subrubro']['subrDescripcion'];?>" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>  >
    </div>
</div><br>

<div class="row">
    <div class="col-xs-4">
        <label style="margin-top: 7px;">Estado: </label>
    </div>
    <div class="col-xs-5">
        <select class="form-control" id="subrEstado"  <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?> >
          <?php 
              echo '<option value="AC" '.($data['subrubro']['subrEstado'] == 'AC' ? 'selected' : '').'>Activo</option>';
              echo '<option value="IN" '.($data['subrubro']['subrEstado'] == 'IN' ? 'selected' : '').'>Inactivo</option>';
          ?>
        </select>
    </div>
  </div>

</div>