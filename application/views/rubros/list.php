<input type="hidden" id="permission" value="<?php echo $permission;?>">
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Rubros</h3>
          <?php
          if (strpos($permission,'Edit') !== false) {
            echo '<button class="btn btn-block btn-success" style="width: 100px; margin-top: 10px;" data-toggle="modal" onclick="LoadRub(0,\'Add\')" id="btnAdd" >Agregar</button>';
          }
          ?>
        </div><!-- /.box-header -->
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
                foreach($list as $r)
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
      </div><!-- /.box -->
    </div><!-- /.col -->
  </div><!-- /.row -->
</section><!-- /.content -->

<script>
  $(function () {
    //$("#groups").DataTable();
    $('#rubros').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": true,
        "language": {
              "lengthMenu": "Ver _MENU_ filas por página",
              "zeroRecords": "No hay registros",
              "info": "Mostrando página _PAGE_ de _PAGES_",
              "infoEmpty": "No hay registros disponibles",
              "infoFiltered": "(filtrando de un total de _MAX_ registros)",
              "sSearch": "Buscar:  ",
              "oPaginate": {
                  "sNext": "Sig.",
                  "sPrevious": "Ant."
                }
          }
      });
  });

  var idRubro = 0;
  var acRubro = '';

  function LoadRub(id_, action){
    idRubro = id_;
    acRubro = action;
    LoadIconAction('modalAction',action);
    WaitingOpen('Cargando rubro');
      $.ajax({
            type: 'POST',
            data: { id : id_, act: action },
        url: 'index.php/rubro/getRubro',
        success: function(result){
                      WaitingClose();
                      $("#modalBodyRubro").html(result.html);
                      setTimeout("$('#modalRubro').modal('show')",800);
              },
        error: function(result){
              WaitingClose();
              ProcesarError(result.responseText, 'modalRubro');
            },
            dataType: 'json'
        });
  }

  $('#btnSave').click(function(){
    if(acRubro == 'View')
    {
      $('#modalRubro').modal('hide');
      return;
    }

    var hayError = false;

    if($('#rubDescripcion').val() == '')
    {
      hayError = true;
    }

    if(hayError == true){
      $('#error').fadeIn('slow');
      return;
    }

    $('#error').fadeOut('slow');
    WaitingOpen('Guardando cambios');
      $.ajax({
            type: 'POST',
            data: {
                    id :      idRubro,
                    act:      acRubro,
                    name:     $('#rubDescripcion').val(),
                    status:   $('#rubEstado').val()
                  },
        url: 'index.php/rubro/setRubro',
        success: function(result){
                      WaitingClose();
                      $('#modalRubro').modal('hide');
                      setTimeout("cargarView('rubro', 'index', '"+$('#permission').val()+"');",1000);
              },
        error: function(result){
              WaitingClose();
              ProcesarError(result.responseText, 'modalRubro');
            },
            dataType: 'json'
        });
  });
</script>


<!-- Modal -->
<div class="modal fade" id="modalRubro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span id="modalAction"> </span> Rubro</h4>
      </div>
      <div class="modal-body" id="modalBodyRubro">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnSave">Guardar</button>
      </div>
    </div>
  </div>
</div>
