<section class="content">
  <div class="row">
    <div class="col-xs-8">
      <div class="box-body">
      <table id="credit" class="table table-bordered table-hover">
        <thead>
          <tr>
          	<th>Fecha</th>
            <th>Concepto</th>
            <th>Debe</th>
            <th>Haber</th>
            <th>Usuario</th>
            <th>-</th>
          </tr>
        </thead>
        <tbody>
	      <?php 
	      $debe = 0;
	      $haber = 0;
	      foreach ($data['data'] as $m) {
	      	echo '<tr>';
	      	echo '<td style="text-align:center">'.date_format(date_create($m['cctepFecha']), 'd-m-Y').'</td>';
	      	echo '<td '.($m['cctepTipo'] == 'RC' ? 'onClick="LoadRec('.$m['cctepRef'].')" style="cursor: pointer"' : '').'>'.$m['cctepConcepto'].'</td>';
	      	echo '<td style="text-align:right">'.number_format ( $m['cctepDebe'] , 2 , "," , "." ).'</td>';
	      	echo '<td style="text-align:right">'.number_format ( $m['cctepHaber'] , 2 , "," , "." ).'</td>';
          echo '<td style="text-align:center">'.$m['usrNick'].'</td>';
          echo '<td style="text-align:center"><i class="fa fa-fw fa-search" style="color: #3c8dbc; cursor: pointer; margin-left: 15px;" onclick="LoadRec('.$m['cctepRef'].',\'View\')"></i></td>';
          // echo '<td style="text-align:center"><a href="#">Ver Detalle</a></td>';
          
         echo '</tr>';
	      	$debe+= $m['cctepDebe'];
	      	$haber+= $m['cctepHaber'];
	      }
	      ?>
	    </tbody>
	  </table>
      </div>
  </div>
  <div class="col-xs-4">
  	<h2>Saldo : <strong style="color: <?php echo $debe - $haber > 0 ? 'red': 'green';?>"> <?php echo number_format ( ($debe - $haber) < 0 ? ($debe - $haber) * -1 : ($debe - $haber)  , 2 , "," , "." );?></strong></h2>
  </div>
 </div>