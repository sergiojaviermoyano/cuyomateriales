<div class="row">
	<div class="col-xs-12">
		<div class="alert alert-danger alert-dismissable" id="error" style="display: none">
	        <h4><i class="icon fa fa-ban"></i> Error!</h4>
	        Revise que todos los campos esten completos
      </div>
	</div>
</div>
<div class="row">
  <div class="col-xs-9">
      <label>
        <h2>
          <strong><?php echo $customer[0]['cliLastName'].', '.$customer[0]['cliName'];?></strong>
        </h2>
      </label>
  </div>
  <div class="col-xs-3">
      <?php
        if($customer[0]['cliImagePath'] == null)
        {
          ?>
            <img src="assets/img/customers/avatar.png" height="100px" class="img-circle" alt="User Image">
          <?php
        }
        else
        {
          ?>
            <img src="assets/img/customers/<?php echo $customer[0]['cliImagePath'];?>" height="100px;" class="img-circle" alt="User Image" style="object-fit: cover; width: 100px;">
          <?php
        }
      ?>
  </div>
</div><br>
<div class="row">
	<div class="col-xs-3">
      <label style="margin-top: 7px;">Fecha <strong style="color: #dd4b39">*</strong>: </label>
  </div>
	<div class="col-xs-9">
      <input type="text" class="form-control" placeholder="dd-mm-aaaa" id="vstFecha_" value="" readonly="">
  </div>
</div><br>
<div class="row">
  <div class="col-xs-3">
      <label style="margin-top: 7px;">Horario <strong style="color: #dd4b39">*</strong>: </label>
  </div>
  <div class="col-xs-2">
      <select class="form-control" id="vstHora_" style="width: 100%;">
        <option value="08">08</option>
        <option value="09">09</option>
        <option value="10">10</option>
        <option value="11">11</option>
        <option value="12">12</option>
        <option value="13">13</option>
        <option value="14">14</option>
        <option value="15">15</option>
        <option value="16">16</option>
        <option value="17">17</option>
        <option value="18">18</option>
        <option value="19">19</option>
        <option value="20">20</option>
        <option value="21">21</option>
        <option value="22">22</option>
      </select>
  </div>
  <div class="col-xs-1">
    :
  </div>
  <div class="col-xs-2">
      <select class="form-control" id="vstMinutos_" style="width: 100%;">
        <option value="00">00</option>
        <option value="15">15</option>
        <option value="30">30</option>
        <option value="45">45</option>
      </select>
  </div>
</div><br>
<div class="row">
  <div class="col-xs-3">
      <label style="margin-top: 7px;">Nota: </label>
  </div>
  <div class="col-xs-9">
      <textarea placeholder="Agregar una Nota" class="form-control" rows="3" id="vstNote_" value=""><?php echo $customer[0]['note'];?></textarea>
  </div>
</div><br>