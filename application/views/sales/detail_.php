<div class="row">
	<div class="col-xs-12">
		<div class="alert alert-danger alert-dismissable" id="error" style="display: none">
	        <h4><i class="icon fa fa-ban"></i> Error!</h4>
	        Revise que todos los campos esten completos
      </div>
	</div>
</div>

<div class="row">
	<div class="col-xs-2">
      Nro:
    </div>
	<div class="col-xs-2">
      <label><?php echo str_pad($data['sale']['venId'], 10, "0", STR_PAD_LEFT);?> </label>
    </div>
    <div class="col-xs-2 col-xs-offset-2">
      Fecha:
    </div>
	<div class="col-xs-2">
      <label><?php 
      			$date = new DateTime($data['sale']['venFecha']);
      			echo $date->format('d-m-Y H:i:s');
      		;?> 
      </label>
    </div>
    <div class="col-xs-1 col-xs-offset-1">
       <?php echo ($data['sale']['venEstado'] === 'AC' ? '<small class="label bg-green">AC</small>': '<small class="label bg-red">CN</small>');?>
    </div>
</div><br>

<div class="row">
	<div class="col-xs-2">
      Caja:
    </div>
	<div class="col-xs-2">
      <label><?php echo str_pad($data['sale']['cajaId'], 10, "0", STR_PAD_LEFT);?> </label>
    </div>
    <div class="col-xs-2 col-xs-offset-2">
      Usuario:
    </div>
    <div class="col-xs-4">
      <label><?php echo $data['sale']['usrName'].', '.$data['sale']['usrLastName'];?> 
      </label>
    </div>
</div>
<hr>

<div class="row">
	<div class="col-xs-12">
	<table class="table table-bordered">
	    <thead>
	        <tr>
	          <th width="10%">Código</th>
	          <th>Descripción</th>
	          <th width="10%">P.Unitario</th>
	          <th width="10%">Cantidad</th>
	          <th width="10%">Total</th>
	        </tr>
	    </thead>
	<?php
		$total = 0;
		foreach ($data['detail'] as $art) {
			echo '<tr>';	
			echo '	<td>'.$art['artCode'].'</td>';
			echo '	<td>'.$art['artDescription'].'</td>';
			echo '	<td style="text-align: right">'.number_format($art['artFinal'], 2, ',', '.').'</td>';
			echo '	<td style="text-align: right">'.$art['venCant'].'</td>';
			echo '	<td style="text-align: right">'.number_format(($art['artFinal'] * $art['venCant']), 2, ',', '.').'</td>';
			echo '</tr>';

			$total += $art['artFinal'] * $art['venCant'];
		}
	?>
		</table>
	</div>
</div>
<hr>

<div class="row">
	<div class="col-xs-1 col-xs-offset-7">
      <h3 style="margin-top: 30px;">Total:</h3>
    </div>
    <div class="col-xs-4" style="text-align:right">
      <h1><?php echo number_format($total, 2, ',', '.');?></h1>
    </div>
</div><br>
