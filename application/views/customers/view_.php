
<div class="row">
	<div class="col-xs-12">
		<div class="alert alert-danger alert-dismissable" id="errorCust" style="display: none">
	        <h4><i class="icon fa fa-ban"></i> Error!</h4>
	        Revise que todos los campos esten completos
      </div>
	</div>
</div>
      <div class="row">
        <div class="col-xs-4">
            <label style="margin-top: 7px;">Nro. Cliente <strong style="color: #dd4b39">*</strong>: </label>
          </div>
        <div class="col-xs-5">
            <input type="text" class="form-control" placeholder="" id="cliId" value="<?php echo $data['customer']['cliId'];?>" disabled="disabled" >
          </div>
      </div><br>
      <div class="row">
        <div class="col-xs-4">
            <label style="margin-top: 7px;">Nombre <strong style="color: #dd4b39">*</strong>: </label>
          </div>
        <div class="col-xs-5">
            <input type="text" class="form-control" placeholder="Sergio Javier" id="cliNombre" value="<?php echo $data['customer']['cliNombre'];?>" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>  >
          </div>
      </div><br>
      <div class="row">
        <div class="col-xs-4">
            <label style="margin-top: 7px;">Apellido <strong style="color: #dd4b39">*</strong>: </label>
          </div>
        <div class="col-xs-5">
            <input type="text" class="form-control" placeholder="Moyano" id="cliApellido" value="<?php echo $data['customer']['cliApellido'];?>" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>  >
          </div>
      </div><br>
      <div class="row">
        <div class="col-xs-4">
            <label style="margin-top: 7px;">Tipo Documento <strong style="color: #dd4b39">*</strong>: </label>
          </div>
        <div class="col-xs-5">
            <select class="form-control" id="docId"  <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?> >
              <?php 
                foreach($data['docs'] as $d){
                  echo '<option value="'.$d['docId'].'" '.($data['customer']['docId'] == $d['docId'] ? 'selected' : '').'>'.$d['docDescripcion'].'</option>';
                }
              ?>
            </select>
          </div>
      </div><br>
      <div class="row">
        <div class="col-xs-4">
            <label style="margin-top: 7px;">Documento <strong style="color: #dd4b39">*</strong>: </label>
          </div>
        <div class="col-xs-5">
            <input type="text" class="form-control" placeholder="3124209" id="cliDocumento" value="<?php echo $data['customer']['cliDocumento'];?>" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>  maxlength="13">
          </div>
      </div><br>
      <div class="row">
        <div class="col-xs-4">
            <label style="margin-top: 7px;">Domicilio: </label>
          </div>
        <div class="col-xs-5">
            <input type="input" class="form-control" placeholder="ej: Barrio Conjunto 4 M/F Casa/19" id="cliDomicilio" value="<?php echo $data['customer']['cliDomicilio'];?>" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>  >
          </div>
      </div><br>
      <div class="row">
        <div class="col-xs-4">
            <label style="margin-top: 7px;">Teléfono: </label>
          </div>
        <div class="col-xs-5">
            <input type="text" class="form-control" placeholder="0264 - 4961020" id="cliTelefono" value="<?php echo $data['customer']['cliTelefono'];?>" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?> >
          </div>
      </div><br>
      <div class="row">
        <div class="col-xs-4">
            <label style="margin-top: 7px;">Mail: </label>
          </div>
        <div class="col-xs-5">
            <input type="text" class="form-control" placeholder="sergio.moyano@outlook.com.ar" id="cliMail" value="<?php echo $data['customer']['cliMail'];?>" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?> >
          </div>
      </div><br>
      <div class="row">
        <div class="col-xs-4">
          <label style="margin-top: 7px;">Estado: </label>
        </div>
        <div class="col-xs-5">
          <select class="form-control" id="cliEstado"  <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?> >
              <?php 
                  echo '<option value="AC" '.($data['customer']['cliEstado'] == 'AC' ? 'selected' : '').'>Activo</option>';
                  echo '<option value="IN" '.($data['customer']['cliEstado'] == 'IN' ? 'selected' : '').'>Inactivo</option>';
              ?>
          </select>
        </div>
      </div>