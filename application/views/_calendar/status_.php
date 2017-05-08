<div class="row">
	<div class="col-xs-12">
		<div class="alert alert-danger alert-dismissable" id="error_s" style="display: none">
	        <h4><i class="icon fa fa-ban"></i> Error!</h4>
	        Ingrese el importe que se abona.
      </div>
	</div>
</div>
<div class="row">
	<div class="col-xs-9">
      <label>
        <h2 style="margin-top: 0px; margin-buttom: 0px;">
          <strong><?php echo $data[0]['cliLastName'].', '.$data[0]['cliName'];?></strong>
        </h2><br>
        <?php echo $data[0]['cliAddress'];?><br>
        <?php 
          if($data[0]['note'] != ""){
            ?>
              <h6>"<?php echo $data[0]['note'];?>"</h6>
            <?php
          }
        ?>
      </label>
  </div>
	<div class="col-xs-3">
      <?php
        if($data[0]['cliImagePath'] == null)
        {
          ?>
            <img src="assets/img/customers/avatar.png" height="100px" class="img-circle" alt="User Image">
          <?php
        }
        else
        {
          ?>
            <img src="assets/img/customers/<?php echo $data[0]['cliImagePath'];?>" height="100px;" class="img-circle" alt="User Image" style="object-fit: cover; width: 100px;">
          <?php
        }
      ?>
  </div>
</div><br>
<div class="row">
	<div class="col-xs-3">
      <h2><label style="margin-top: 7px;">Saldo: </label></h2>
  </div>
	<div class="col-xs-9">
      <h2>
      <?php
      if($data[0]['balance'] < 0){
        ?><i class="fa fa-fw fa-plus" style="color: #00a65a"></i><?php
        echo str_replace('-', '', $data[0]['balance']);
      }
      else{
            if($data[0]['balance'] == 0){
              ?><i class="fa fa-fw fa-check" style="color: #3c8dbc"></i><?php
              echo $data[0]['balance'];
            }
            else{
                  ?><i class="fa fa-fw fa-minus" style="color: #dd4b39"></i><?php
                  echo $data[0]['balance'];
                }
          }
      ?>
      </h2>
  </div>
</div><br>
<div class="row">
  <div class="col-xs-3">
      <h4><label style="margin-top: 7px;">Entrega <strong style="color: #dd4b39">*</strong>: </label></h4>
  </div>
  <div class="col-xs-9">
      <input type="text" class="form-control" placeholder="Importe a Cuenta" id="statusPrice" value="" >
  </div>
</div><br>
<div class="row">
  <div class="col-xs-3">
      <label style="margin-top: 7px;">Nota: </label>
  </div>
  <div class="col-xs-9">
      <textarea placeholder="Agregar una Nota" class="form-control" rows="3" id="statusNote" value=""></textarea>
  </div>
</div><br>

<input type="hidden" id="cliId" value="<?php echo $data[0]['cliId']?>">
<input type="hidden" id="vstId" value="<?php echo $data[0]['vstId']?>">