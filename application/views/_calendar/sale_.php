<div class="row">
	<div class="col-xs-12">
		<div class="alert alert-danger alert-dismissable" id="error_s" style="display: none">
	        <h4><i class="icon fa fa-ban"></i> Error!</h4>
	        Revise los campos obligatorios.
      </div>
	</div>
</div>
<div class="row">
  <div class="col-xs-3">
      <label style="margin-top: 7px;">Cliente <strong style="color: #dd4b39">*</strong>: </label>
  </div>
  <div class="col-xs-9">
    <div class="col-xs-9">
      <select class="form-control select2" id="cliId_s" style="width: 100%;">
        <?php 
          echo '<option value="-1" selected></option>';
          foreach ($customers as $c) {
            echo '<option value="'.$c['cliId'].'" data-balance="'.$c['balance'].'" data-address="'.$c['cliAddress'].'" data-dni="'.$c['cliDni'].'">'.$c['cliLastName'].', '.$c['cliName'].'</option>';
          }
        ?>
      </select>
    </div>
  </div>
</div><br>
<div class="row">
  <div class="col-xs-2">
      <label>Saldo: </label>
  </div>
  <div class="col-xs-4">
      <label id="textBalance"></label>
      <label id="importBalance"></label>
  </div>
  <div class="col-xs-2">
      <label>Dni: </label>
  </div>
  <div class="col-xs-4">
      <label id="dniNumber"></label>
  </div>
</div>
<div class="row">
  <div class="col-xs-2">
      <label>Domicilio: </label>
  </div>
  <div class="col-xs-10">
      <label id="address"></label>
  </div>
</div>
<div class="row">
  <div class="col-xs-12">
      <hr>
  </div>
</div>
<div class="row">
  <div class="col-xs-3">
      <label style="margin-top: 7px;">Producto <strong style="color: #dd4b39">*</strong>: </label>
  </div>
  <div class="col-xs-9">
    <div class="col-xs-9">
      <select class="form-control select2" id="prodId_s" style="width: 100%;">
        <?php 
          echo '<option value="-1" selected></option>';
          foreach ($products as $p) {
            echo '<option value="'.$p['prodId'].'" data-price="'.$p['prodPrice'].'" data-margin="'.$p['prodMargin'].'">'.$p['prodCode'].' - '.$p['prodDescription'].'</option>';
          }
        ?>
      </select>
    </div>
  </div>
</div><br>
<div class="row">
  <div class="col-xs-2">
      <label style="margin-top: 7px;">Precio: </label>
  </div>
  <div class="col-xs-2" style="margin-top: 7px;">
      $ <label id="prodPrice"></label>
  </div>
  <div class="col-xs-2">
      <label style="margin-top: 7px;">Cant: </label>
  </div>
  <div class="col-xs-1">
    <i class="fa fa-fw fa-minus" style="color: red; margin-top: 8px;" id="sub"></i>
  </div>
  <div class="col-xs-2">
      <input type="text" class="form-control" id="cant_" readonly="readonly" value="1" style="text-align: right">
  </div>
  <div class="col-xs-1">
    <i class="fa fa-fw fa-plus" style="color: green; margin-top: 8px;" id="sum"></i>
  </div>
  <div class="col-xs-1">
      <button class="btn btn-success" id="addProduct"> <i class="fa fa-fw fa-check"></i> </button>
  </div>
</div>
<div class="row">
  <div class="col-xs-12">
      <table id="products" class="table table-condensed">
          <thead>
            <tr>
              <th style="width:5%">#</th>
              <th>Producto <strong style="color: #dd4b39">*</strong></th>
              <th style="width:15%">Precio</th>
              <th style="width:5%">Cant.</th>
              <th style="width:15%">Total</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
      </table>
  </div>
</div>
<div class="row">
  <div class="col-xs-2">
      <label>Entrega $</label>
  </div>
  <div class="col-xs-4">
      <input type="text" id="to_acount">
  </div>
  <div class="col-xs-2">
      <hr>
  </div>
  <div class="col-xs-2">
      <label>Total: </label>
  </div>
  <div class="col-xs-2 pull-right">
      <label id="total">0.00</label>
  </div>
</div>
<div class="row">
  <div class="col-xs-12">
    <hr>
  </div>
</div>
<div class="row">
  <div class="col-xs-4">
      <label style="margin-top: 7px;">Próxima visita <strong style="color: #dd4b39">*</strong>:</label>
  </div>
  <div class="col-xs-3">
      <input type="text" class="form-control" placeholder="dd-mm-aaaa" id="vstFecha__s" value="" readonly="">
  </div>
  <div class="col-xs-2">
      <select class="form-control" id="vstHora__s" style="width: 100%;">
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
      <select class="form-control" id="vstMinutos__s" style="width: 100%;">
        <option value="00">00</option>
        <option value="15">15</option>
        <option value="30">30</option>
        <option value="45">45</option>
      </select>
  </div>
</div><br>
<div class="row">
  <div class="col-xs-3">
      <label style="margin-top: 7px;">Nota </label>
  </div>
  <div class="col-xs-9">
      <textarea placeholder="Agregar una Nota" class="form-control" rows="3" id="vstNote__s" value=""></textarea>
  </div>
</div>