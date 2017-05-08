<div class="row">
	<div class="col-xs-12">
		<div class="alert alert-danger alert-dismissable" id="error" style="display: none">
	        <h4><i class="icon fa fa-ban"></i> Error!</h4>
	        Revise que todos los campos esten completos
      </div>
	</div>
</div>
<?php
if($data['read'] == true){
  $date = date_create($data['motion']['crdDate']);
  ?>
    <div class="row">
      <div class="col-xs-3">
          <label style="margin-top: 7px;">Fecha <strong style="color: #dd4b39">*</strong>: </label>
      </div>
      <div class="col-xs-9">
          <input type="text" class="form-control" placeholder="Detalle" id="crdDescription" value="<?php echo date_format($date, 'd-m-Y H:i');?>" disabled="disabled"> 
      </div>
    </div><br>
  <?php
}
?>
<div class="row">
	<div class="col-xs-3">
      <label style="margin-top: 7px;">Cliente <strong style="color: #dd4b39">*</strong>: </label>
    </div>
	<div class="col-xs-9">
      <select class="form-control select2" id="cliId" style="width: 100%;" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>>
        <?php 
          echo '<option value="-1" selected></option>';
          foreach ($data['customers'] as $c) {
            echo '<option value="'.$c['cliId'].'" '.($data['motion']['cliId'] == $c['cliId'] ? 'selected' : '').'>'.$c['cliLastName'].', '.$c['cliName'].'</option>';
          }
        ?>
      </select>
    </div>
</div><br>
<div class="row">
	<div class="col-xs-3">
      <label style="margin-top: 7px;">Descripción <strong style="color: #dd4b39">*</strong>: </label>
  </div>
	<div class="col-xs-9">
      <input type="text" class="form-control" placeholder="Detalle" id="crdDescription" value="<?php echo $data['motion']['crdDescription'];?>" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>  >
  </div>
</div><br>
<div class="row">
  <div class="col-xs-3">
      <label style="margin-top: 7px;"><i class="fa fa-fw fa-plus" style="color: #00a65a"></i>: </label>
  </div>
  <div class="col-xs-9">
      <input type="text" class="form-control" placeholder="Debe" id="crdDebe" value="<?php echo $data['motion']['crdDebe'];?>" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>  >
  </div>
</div><br>
<div class="row">
  <div class="col-xs-3">
      <label style="margin-top: 7px;"><i class="fa fa-fw fa-minus" style="color: #dd4b39"></i>: </label>
  </div>
  <div class="col-xs-9">
      <input type="text" class="form-control" placeholder="Haber" id="crdHaber" value="<?php echo $data['motion']['crdHaber'];?>" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>  >
  </div>
</div><br>
<div class="row">
  <div class="col-xs-3">
      <label style="margin-top: 7px;">Nota <strong style="color: #dd4b39">*</strong>: </label>
  </div>
  <div class="col-xs-9">
      <input type="text" class="form-control" placeholder="Motivo" id="crdNote" value="<?php echo $data['motion']['crdNote'];?>" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>  >
  </div>
</div>
</div>