<div class="row">
	<div class="col-xs-12">
		<div class="box-body">
          <table id="articles" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th width="1%"></th>
                <th width="15%">N° Orden / Observación</th>
                <th width="15%">Importe</th>
                <th width="15%">Lista de Precio</th>
                <th >Usuario</th>
              </tr>
            </thead>
            <tbody>
            <?php
                $total = 0;
            	foreach ($data as $item) {
            		echo '<tr>';
                    echo '<td><i class="fa fa-fw fa-print" style="color: #A4A4A4; cursor: pointer; margin-left: 15px;" onclick="Print('.$item['ocId'].')"></i></td>';
            		echo '<td>'.$item['ocId'].' / '.$item['ocObservacion'].'</td>';
            		echo '<td style="text-align: right">'.number_format($item['importe'], 2, ',', '.').'</td>';
                    echo '<td>'.$item['lpDescripcion'].'</td>';
            		echo '<td>'.$item['usrNick'].'</td>';
            		echo '</tr>';

                    $total += $item['importe'];
            	}

                echo '<tr>';
                echo '<td colspan="2" style="text-align: right"><h3>Total:</h3></td>';
                echo '<td style="text-align: right"><h3>$'.number_format($total, 2, ',', '.').'</h3></td>';
                echo '</tr>';
            ?>
            </tbody>
          </table>
        </div><!-- /.box-body -->
	</div>
</div>