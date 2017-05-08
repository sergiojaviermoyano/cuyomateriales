<div class="row">
	<div class="col-xs-12">
		<div class="alert alert-danger alert-dismissable" id="errorUsr" style="display: none">
	        <h4><i class="icon fa fa-ban"></i> Error!</h4>
	        Revise que todos los campos esten completos
      </div>
	</div>
</div>
<div class="row">
	<div class="col-xs-4">
      <label style="margin-top: 7px;">Usuario <strong style="color: #dd4b39">*</strong>: </label>
    </div>
	<div class="col-xs-5">
      <input type="text" class="form-control" placeholder="Usuario" id="usrNick" value="<?php echo $data['user']['usrNick'];?>" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>  >
    </div>
</div><br>
<div class="row">
	<div class="col-xs-4">
      <label style="margin-top: 7px;">Nombre <strong style="color: #dd4b39">*</strong>: </label>
    </div>
	<div class="col-xs-5">
      <input type="text" class="form-control" placeholder="Nombre" id="usrName" value="<?php echo $data['user']['usrName'];?>" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>  >
    </div>
</div><br>
<div class="row">
	<div class="col-xs-4">
      <label style="margin-top: 7px;">Apellido <strong style="color: #dd4b39">*</strong>: </label>
    </div>
	<div class="col-xs-5">
      <input type="text" class="form-control" placeholder="Apellido" id="usrLastName" value="<?php echo $data['user']['usrLastName'];?>" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>  >
    </div>
</div><br>
<div class="row">
	<div class="col-xs-4">
      <label style="margin-top: 7px;">Comisión <strong style="color: #dd4b39">*</strong>: </label>
    </div>
	<div class="col-xs-5">
      <input type="text" class="form-control" placeholder="Comisión" id="usrComision" value="<?php echo $data['user']['usrComision'];?>" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>  >
    </div>
</div><br>
<div class="row">
	<div class="col-xs-4">
      <label style="margin-top: 7px;">Contraseña <strong style="color: #dd4b39">*</strong>: </label>
    </div>
	<div class="col-xs-5">
      <input type="password" class="form-control" placeholder="••••••••" id="usrPassword" value="" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>  >
    </div>
</div><br>
<div class="row">
	<div class="col-xs-4">
      <label style="margin-top: 7px;">Confirma Contraseña <strong style="color: #dd4b39">*</strong>: </label>
    </div>
	<div class="col-xs-5">
      <input type="password" class="form-control" placeholder="••••••••" id="usrPasswordConf" value="" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>  >
    </div>
</div><br>
<div class="row">
	<div class="col-xs-4">
      <label style="margin-top: 7px;">Grupo <strong style="color: #dd4b39">*</strong>: </label>
    </div>
	<div class="col-xs-5">
      <select class="form-control" id="grpId"  <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?> >
        <?php 
        	foreach ($data['groups'] as $g) {
        		echo '<option value="'.$g['grpId'].'" '.($data['user']['grpId'] == $g['grpId'] ? 'selected' : '').'>'.$g['grpName'].'</option>';
        	}
        ?>
      </select>
    </div>
</div>