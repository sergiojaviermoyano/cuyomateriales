
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
            <label style="margin-top: 7px;">Nro. Proveedor <strong style="color: #dd4b39">*</strong>: </label>
          </div>
        <div class="col-xs-5">
            <input type="text" class="form-control" placeholder="" id="prvId" value="<?php echo $data['provider']['prvId'];?>" disabled="disabled" >
          </div>
      </div><br>
      <div class="row">
        <div class="col-xs-4">
            <label style="margin-top: 7px;">Nombre : </label>
          </div>
        <div class="col-xs-5">
            <input type="text" class="form-control" placeholder="Sergio Javier" id="prvNombre" value="<?php echo $data['provider']['prvNombre'];?>" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>  >
          </div>
      </div><br>
      <div class="row">
        <div class="col-xs-4">
            <label style="margin-top: 7px;">Apellido : </label>
          </div>
        <div class="col-xs-5">
            <input type="text" class="form-control" placeholder="Moyano" id="prvApellido" value="<?php echo $data['provider']['prvApellido'];?>" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>  >
          </div>
      </div><br>
      <div class="row">
        <div class="col-xs-4">
            <label style="margin-top: 7px;">Razón Social <strong style="color: #dd4b39">*</strong>: </label>
          </div>
        <div class="col-xs-5">
            <input type="text" class="form-control" placeholder="Indevla.com" id="prvRazonSocial" value="<?php echo $data['provider']['prvRazonSocial'];?>" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>  >
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
                  echo '<option value="'.$d['docId'].'" '.($data['provider']['docId'] == $d['docId'] ? 'selected' : '').'>'.$d['docDescripcion'].'</option>';
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
            <input type="text" class="form-control" placeholder="3124209" id="prvDocumento" value="<?php echo $data['provider']['prvDocumento'];?>" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>  maxlength="13">
          </div>
      </div><br>
      <div class="row">
        <div class="col-xs-4">
            <label style="margin-top: 7px;">Domicilio: </label>
          </div>
        <div class="col-xs-5">
            <input type="input" class="form-control" placeholder="ej: Barrio Conjunto 4 M/F Casa/19" id="prvDomicilio" value="<?php echo $data['provider']['prvDomicilio'];?>" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>  >
          </div>
      </div><br>
      <div class="row">
        <div class="col-xs-4">
            <label style="margin-top: 7px;">Teléfono: </label>
          </div>
        <div class="col-xs-5">
            <input type="text" class="form-control" placeholder="0264 - 4961020" id="prvTelefono" value="<?php echo $data['provider']['prvTelefono'];?>" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?> >
          </div>
      </div><br>
      <div class="row">
        <div class="col-xs-4">
            <label style="margin-top: 7px;">Mail: </label>
          </div>
        <div class="col-xs-5">
            <input type="text" class="form-control" placeholder="sergio.moyano@outlook.com.ar" id="prvMail" value="<?php echo $data['provider']['prvMail'];?>" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?> >
          </div>
      </div><br>
      <div class="row">
        <div class="col-xs-4">
          <label style="margin-top: 7px;">Estado: </label>
        </div>
        <div class="col-xs-5">
          <select class="form-control" id="prvEstado"  <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?> >
              <?php 
                  echo '<option value="AC" '.($data['provider']['prvEstado'] == 'AC' ? 'selected' : '').'>Activo</option>';
                  echo '<option value="IN" '.($data['provider']['prvEstado'] == 'IN' ? 'selected' : '').'>Inactivo</option>';
              ?>
          </select>
        </div>
      </div>