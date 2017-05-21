<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
          <?php
          if ($data['cajaId'] == -1) {
            ?>
            <div class="box-header">
              <h3>No hay cajas abiertas para poder cobrar </h3>
            </div>
            <?php
          } else {
          ?>
            <div class="box-body">
              <table id="rubros" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th width="20%">Acciones</th>
                    <th width="10%">Número</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    foreach($data['ventas'] as $v)
                    {
                            echo '<tr>';
                            echo '<td>';
                            //echo '<i class="fa fa-fw fa-ban" style="color: #dd4b39; cursor: pointer; margin-left: 15px;" onclick="LoadRub('.$r['rubId'].',\'View\')"></i>';
                            echo '</td>';
                            echo '<td style="text-align: center"> 0000-'.str_pad($v['venId'], 8, "0", STR_PAD_LEFT).'</td>';
                            echo '<td style="text-align: center">'.date("d-m-Y H:i", strtotime($v['venFecha'])).'</td>';
                            echo '<td style="text-align: center">';
                            switch($v['venEstado']){
                              case 'AC':
                                echo '<small class="label pull-left bg-green">Activa</small>';
                                break;

                              case 'AN':
                                echo '<small class="label pull-left bg-red">Anulada</small>';
                                break;
                            }
                            echo '</td>';
                            echo '</tr>';
                    }
                  ?>
                </tbody>
              </table>
            </div><!-- /.box-body -->
          <?php
          }
          ?>
      </div><!-- /.box -->
    </div><!-- /.col -->
  </div><!-- /.row -->
</section><!-- /.content -->