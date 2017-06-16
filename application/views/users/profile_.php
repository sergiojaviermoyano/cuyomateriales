<div class="row">
	<div class="col-xs-12">
		<div class="alert alert-danger alert-dismissable" id="errorProfile_" style="display: none">
	        <h4><i class="icon fa fa-ban"></i> Error!</h4>
	        Revise que todos los campos esten completos
      </div>
	</div>
</div>
<div class="row">
	<div class="col-xs-4">
      <label style="margin-top: 7px;">Nombre <strong style="color: #dd4b39">*</strong>: </label>
    </div>
	<div class="col-xs-5">
      <input type="text" class="form-control" placeholder="Usuario" id="usrName" value="<?php echo $data['user']['usrName'];?>">
    </div>
</div><br>
<div class="row">
	<div class="col-xs-4">
      <label style="margin-top: 7px;">Apellido <strong style="color: #dd4b39">*</strong>: </label>
    </div>
	<div class="col-xs-5">
      <input type="text" class="form-control" placeholder="Nombre" id="usrLastName" value="<?php echo $data['user']['usrLastName'];?>">
    </div>
</div><br>
<div class="row">
	<div class="col-xs-12"><hr></div>
</div><br>
<div class="row">
	<div class="col-xs-4">
      <label style="margin-top: 7px;">Contraseña <strong style="color: #dd4b39">*</strong>: </label>
    </div>
	<div class="col-xs-5">
      <input type="password" class="form-control" placeholder="••••••••" id="usrPassword" value="">
    </div>
</div><br>
<div class="row">
	<div class="col-xs-4">
      <label style="margin-top: 7px;">Confirmar Contraseña <strong style="color: #dd4b39">*</strong>: </label>
    </div>
	<div class="col-xs-5">
      <input type="password" class="form-control" placeholder="••••••••" id="usrPasswordConfirm" value="">
    </div>
</div><br>