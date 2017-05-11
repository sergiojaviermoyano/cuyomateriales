<input type="hidden" id="permission" value="<?php echo $permission;?>">
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Listas de Precios</h3>
          <?php
          if (strpos($permission,'Add') !== false) {
            echo '<button class="btn btn-block btn-success" style="width: 100px; margin-top: 10px;" data-toggle="modal" onclick="LoadLista(0,\'Add\')" id="btnAdd" >Agregar</button>';
          }
          ?>
        </div><!-- /.box-header -->
        <div class="box-body">
          <table id="listas" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th width="20%">Acciones</th>
                <th>Descripción</th>
                <th>Margen</th>
                <th>Lista Principal</th>
                <th>Estado</th>
              </tr>
            </thead>
            <tbody>
              <?php
                if($list != '')
                foreach($list as $l)
                {
                    echo '<tr>';
                    echo '<td>';
                    if (strpos($permission,'Edit') !== false) {
                      echo '<i class="fa fa-fw fa-pencil" style="color: #f39c12; cursor: pointer; margin-left: 15px;" onclick="LoadLista('.$l['lpId'].',\'Edit\')"></i>';
                    }
                    if (strpos($permission,'Del') !== false) {
                      echo '<i class="fa fa-fw fa-times-circle" style="color: #dd4b39; cursor: pointer; margin-left: 15px;" onclick="LoadLista('.$l['lpId'].',\'Del\')"></i>';
                    }
                    if (strpos($permission,'View') !== false) {
                      echo '<i class="fa fa-fw fa-search" style="color: #3c8dbc; cursor: pointer; margin-left: 15px;" onclick="LoadLista('.$l['lpId'].',\'View\')"></i>';
                    }
                    echo '</td>';
                    echo '<td style="text-align: left">'.$l['lpDescripcion'].'</td>';
                    echo '<td style="text-align: right">'.$l['lpMargen'].' %</td>';
                    echo '<td style="text-align: center">';
                      if($l['lpDefault'])
                        echo '<i class="fa fa-fw fa-circle" style="color: #00a65a; cursor: pointer; margin-left: 15px;"></i>';
                    echo'</td>';
                    echo '<td style="text-align: center">';
                    switch($l['lpEstado']){
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
    $('#listas').DataTable({
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

  var idLista = 0;
  var acLista = '';

  function LoadLista(id_, action){
    idLista = id_;
    acLista = action;
    LoadIconAction('modalAction',action);
    WaitingOpen('Cargando lista');
      $.ajax({
            type: 'POST',
            data: { id : id_, act: action },
        url: 'index.php/lista/getLista', 
        success: function(result){
                      WaitingClose();
                      $("#modalBodyLista").html(result.html);
                      $("#lpMargen").maskMoney({allowNegative: true, thousands:'', decimal:'.'});
                      setTimeout("$('#modalLista').modal('show')",800);
                      //$("[data-mask]").inputmask();
              },
        error: function(result){
              WaitingClose();
              ProcesarError(result.responseText, 'modalLista');
            },
            dataType: 'json'
        });
  }

  $('#btnSave').click(function(){
    if(acLista == 'View')
    {
      $('#modalLista').modal('hide');
      return;
    }

    var hayError = false;

    if($('#lpDescripcion').val() == '')
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
                    id :      idLista, 
                    act:      acLista, 
                    name:     $('#lpDescripcion').val(), 
                    margin:   $('#lpMargen').val(),
                    def:      $('#lpDefault').prop('checked'),
                    status:   $('#lpEstado').val()
                  },
        url: 'index.php/lista/setLista', 
        success: function(result){
                      WaitingClose();
                      $('#modalLista').modal('hide');
                      setTimeout("cargarView('lista', 'index', '"+$('#permission').val()+"');",1000);
              },
        error: function(result){
              WaitingClose();
              ProcesarError(result.responseText, 'modalLista');
            },
            dataType: 'json'
        });
  });
</script>


<!-- Modal -->
<div class="modal fade" id="modalLista" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span id="modalAction"> </span> Lista de Precio</h4> 
      </div>
      <div class="modal-body" id="modalBodyLista">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnSave">Aceptar</button>
      </div>
    </div>
  </div>
</div>