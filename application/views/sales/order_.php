<?php 
//var_dump($data);
?>
<div class="row">
	<div class="col-xs-1"><i class="fa fa-fw fa-bar-chart-o"></i></div>
	<div class="col-xs-5"><?php echo $data['lista']['lpDescripcion'];?></div>
  <!--<div class="col-xs-1"><i class="fa fa-fw fa-user"></i></div>
  <div class="col-xs-5"><?php echo $data['order']['cliId'];?></div>-->
</div> <br>
<div class="row">
	<div class="col-xs-1"><i class="fa fa-fw fa-comment"></i></div>
	<div class="col-xs-5"><strong><?php echo $data['order']['ocObservacion'];?></strong></div>
	<div class="col-xs-1"><i class="fa fa-fw fa-edit"></i></div>
	<div class="col-xs-2"><?php echo $data['user']['usrName'].', '.$data['user']['usrLastName'];?></div>
	<div class="col-xs-3"><?php echo date("d-m-Y H:i", strtotime($data['order']['ocFecha']));?></div>
</div>  <br>

<!--
<div class="row">
  <div class="col-xs-2 col-xs-offset-1"><center>Cantidad</center></div>
  <div class="col-xs-8"><center>Producto</center></div>
</div>
<div class="row">
  <div class="col-xs-2 col-xs-offset-1">
    <input type="number" class="form-control" id="artCant" value="1" min="1">
  </div>
  <div class="col-xs-7">
    <input type="number" class="form-control" id="artId" value="" min="0">
  </div>
  <div class="col-xs-2"><button type="button" class="btn btn-success" id="btnAddProd"><i class="fa fa-check"></i></button></div>
</div><br>
-->
<div class="row">
  <div class="col-xs-10 col-xs-offset-1">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th width="35px"></th>
          <th width="10%">Código</th>
          <th>Descripción</th>
          <th width="10%">P.Unitario</th>
          <th width="10%">Cantidad</th>
          <th width="10%">Total</th>
        </tr>
      </thead>
    </table>
    <table id="saleDetail" style="display:block; overflow: auto; display: table; max-height:15em;" class="table table-bordered" width="100%">
  		<?php 
  		$items = 0;
  		$total = 0;
  		foreach ($data['orderdetalle'] as $key => $item):
  			echo '<tr>';
  			echo '<td width="35px"></td>';
			echo '<td width="10%">'.$item['artBarCode'].'</td>';
			echo '<td>'.$item['artDescripcion'].'</td>';
			echo '<td width="10%" style="text-align: right">'.$item['artPVenta'].'</td>';
			echo '<td width="10%" style="text-align: right">'.$item['ocdCantidad'].'</td>';
			echo '<td width="10%" style="text-align: right">'.number_format(($item['artPVenta'] * $item['ocdCantidad']),2).'</td>';
			echo '</tr>';
			$items += $item['ocdCantidad'];
			$total += ($item['artPVenta'] * $item['ocdCantidad']);
		endforeach;?>
    </table>
  </div>
</div>

<div class="row">
  <div class="col-xs-1 col-xs-offset-1">
      <label style="font-size: 20px; margin-top: 10px;" id="saleItems"><?php echo $items;?></label>
  </div>

  <div class="col-xs-2 col-xs-offset-4">
      <label style="font-size: 20px; margin-top: 10px;">Total</label>
  </div>
  <div class="col-xs-3 text-right">
      <label style="font-size: 30px; color: red;" id="saleTotal"><?php echo number_format($total, 2);?></label>
  </div>
</div>