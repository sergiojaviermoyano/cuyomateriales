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
                    <th>Descripción</th>
                    <th>Estado</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    foreach($data['ventas'] as $r)
                    {
                            echo '<tr>';
                            echo '<td>';
                            if (strpos($permission,'Edit') !== false) {
                              echo '<i class="fa fa-fw fa-pencil" style="color: #f39c12; cursor: pointer; margin-left: 15px;" onclick="LoadRub('.$r['rubId'].',\'Edit\')"></i>';
                            }
                            if (strpos($permission,'Del') !== false) {
                              echo '<i class="fa fa-fw fa-times-circle" style="color: #dd4b39; cursor: pointer; margin-left: 15px;" onclick="LoadRub('.$r['rubId'].',\'Del\')"></i>';
                            }
                            if (strpos($permission,'View') !== false) {
                              echo '<i class="fa fa-fw fa-search" style="color: #3c8dbc; cursor: pointer; margin-left: 15px;" onclick="LoadRub('.$r['rubId'].',\'View\')"></i>';
                            }
                            echo '</td>';
                            echo '<td style="text-align: left">'.$r['rubDescripcion'].'</td>';
                            echo '<td style="text-align: center">';
                            switch($r['rubEstado']){
                              case 'AC':
                                echo '<small class="label pull-left bg-green">Activo</small>';
                                break;

                              case 'IN':
                                echo '<small class="label pull-left bg-red">Inactivo</small>';
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