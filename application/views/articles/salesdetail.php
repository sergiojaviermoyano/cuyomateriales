<div class="row">
	<div class="col-xs-12">
		<div class="box-body">
          <table id="articles" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th width="15%">Código</th>
                <th>Artículo</th>
                <th width="15%">Cantidad</th>
              </tr>
            </thead>
            <tbody>
            <?php
            	foreach ($data as $item) {
            		echo '<tr>';
            		echo '<td>'.($item['artCode'] == '' ? '-' : $item['artCode']).'</td>';
            		echo '<td>'.$item['artDescription'].'</td>';
            		echo '<td style="text-align: right">'.$item['ventas'].'</td>';
            		echo '</tr>';
            	}
            ?>
            </tbody>
          </table>
        </div><!-- /.box-body -->
	</div>
</div>