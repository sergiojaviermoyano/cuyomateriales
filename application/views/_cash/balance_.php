<div class="row">
	<div class="col-xs-3">
      <label style="margin-top: 7px;">Cliente <strong style="color: #dd4b39">*</strong>: </label>
    </div>
	<div class="col-xs-9">
      <select class="form-control select2" id="cliId" style="width: 100%;">
        <?php 
          echo '<option value="-1" selected></option>';
          foreach ($data['customers'] as $c) {
            echo '<option value="'.$c['cliId'].'" data-balance="'.$c['balance'].'">'.$c['cliLastName'].', '.$c['cliName'].'</option>';
          }
        ?>
      </select>
    </div>
</div><br>
<div class="row">
	<div class="col-xs-3">
      <br>
      <label style="margin-top: 7px;">Saldo: </label>
  </div>
	<div class="col-xs-2">
      <h2 id="textBalance"></h2>
  </div>
  <div class="col-xs-7">
      <h2 id="importBalance"></h2>
  </div>
</div><br>