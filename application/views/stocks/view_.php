<div class="row">
	<div class="col-xs-12">
		<div class="alert alert-danger alert-dismissable" id="error" style="display: none">
	        <h4><i class="icon fa fa-ban"></i> Error!</h4>
	        Revise que todos los campos esten completos
      </div>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
    <div class="box box-default">
      <div class="box-header with-border">
        <i class="fa fa-file-text"></i>

        <h3 class="box-title">
          <?php 
            echo 'Código: <label>'.$data['art'][0]['artBarCode'].'</label>    ';
            echo 'Artículo: <label>'.$data['art'][0]['artDescription'].'</label>  '; 
            echo 'Desde: <label>'.$data['dateF'].'</label> Hasta: <label>'.$data['dateT'].'</label>';
          ?>
        </h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
      <div class="row">
          <div class="col-xs-12">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th width="35px"></th>
                  <th width="100px">Cantidad</th>
                  <th width="200px">Fecha</th>
                  <th>Origen</th>
                </tr>
              </thead>
            </table>
            <table style="height:15em; display:block; overflow-y: auto;" class="table table-bordered">
              <tbody>
              <?php
                foreach ($data['mov'] as $mov) {
                  $fecha = explode(' ', $mov['stkFecha']);
                  $hora = $fecha[1];
                  $dia = explode('-', $fecha[0]);
                  $fecha = $dia[2].'-'.$dia[1].'-'.$dia[0].' '.$hora;
                  echo '<tr>';
                  echo '<td width="35px">'.($mov['stkCant'] < 0 ? '<i class="fa fa-fw fa-arrow-down" style="color: #dd4b39"></i>' : '<i class="fa fa-fw fa-arrow-up" style="color: #00a65a"></i>').'</td>';
                  echo '<td width="100px">'.($mov['stkCant'] < 0 ? $mov['stkCant']* -1 : $mov['stkCant']).'</td>';
                  echo '<td width="200px">'.$fecha.'</td>';
                  echo '<td>';
                  switch ($mov['stkOrigen'])
                  {
                    case 'RC': 
                              echo 'Recepción';
                              break;

                    case 'VN': 
                              echo 'Venta';
                              break;

                    case 'AJ': 
                              echo 'Ajuste';
                              break;

                    case 'CV': 
                              echo 'Anulación Venta';
                              break;
                  }
                  echo '</tr>';
                }
              ?>
              </tbody>
            </table>
          </div>
        </div>
        
        <br>
        <div class="row">
          <div class="col-xs-4">
            <div class="alert alert-info alert-dismissible">
              <h4><i class="icon fa fa-cubes"></i> Stock Real</h4>
              <h1><?php echo ($data['stk'][0]['cant'] == '' ? '0' : $data['stk'][0]['cant']);?></h1>
            </div>
          </div> 

          <div class="col-xs-4 col-xs-offset-4">
            <div class="alert alert-warning alert-dismissible">
              <h4><i class="icon fa fa-filter"></i> Stock Filtro</h4>
              <h1><?php echo ($data['stkF'][0]['cant'] == '' ? '0' : $data['stkF'][0]['cant']);?></h1>
            </div>
          </div>
        </div>
        <!--
        <div class="alert alert-danger alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <h4><i class="icon fa fa-ban"></i> Alert!</h4>
          Danger alert preview. This alert is dismissable. A wonderful serenity has taken possession of my entire
          soul, like these sweet mornings of spring which I enjoy with my whole heart.
        </div>
        
        <div class="alert alert-warning alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <h4><i class="icon fa fa-warning"></i> Alert!</h4>
          Warning alert preview. This alert is dismissable.
        </div>
        <div class="alert alert-success alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <h4><i class="icon fa fa-check"></i> Alert!</h4>
          Success alert preview. This alert is dismissable.
        </div>
        -->
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
</div>

</div>