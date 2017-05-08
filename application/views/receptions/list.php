<input type="hidden" id="permission" value="<?php echo $permission;?>">
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Recepciones</h3>
          <?php
          if (strpos($permission,'Add') !== false) {
            echo '<button class="btn btn-block btn-success" style="width: 100px; margin-top: 10px;" data-toggle="modal" onclick="LoadRec(0,\'Add\')" id="btnAdd" title="Nueva">Agregar</button>';
          }
          ?>
        </div><!-- /.box-header -->
       
        <div class="box-header">
          <div class="box-tools">
            <div class="input-group input-group-sm" style="width: 150px;">
              <input type="text" id="table_search" class="form-control pull-right" placeholder="Buscar">

              <div class="input-group-btn">
                <button type="button" class="btn btn-default"><i class="fa fa-search"></i></button>
              </div>
            </div>
          </div>
        </div><br>
        <div class="box-body">
          <table id="credit" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th width="10%">Acciones</th>
                <th>Numero</th>
                <th>Fecha</th>
                <th>Proveedor</th>
                <th>Estado</th>
              </tr>
            </thead>
            <tbody>
              <?php
                if(count($list['data']) > 0) {                  
                  foreach($list['data'] as $r)
                  {
                    echo '<tr>';
                    echo '<td>';

                    if($r['recEstado'] == 'AC')
                      if (strpos($permission,'Conf') !== false) {
                          echo '<i class="fa fa-fw fa-check" style="color: #00a65a; cursor: pointer; margin-left: 15px;" onclick="LoadRec('.$r['recId'].',\'Conf\')"></i>';
                      }
                    
                    if($r['recEstado'] == 'AC')
                      if (strpos($permission,'Disc') !== false) {
                        echo '<i class="fa fa-fw fa-ban" style="color: #dd4b39 ; cursor: pointer; margin-left: 15px;" onclick="LoadRec('.$r['recId'].',\'Disc\')"></i>';
                      }

                    if (strpos($permission,'View') !== false) {
                      echo '<i class="fa fa-fw fa-search" style="color: #3c8dbc; cursor: pointer; margin-left: 15px;" onclick="LoadRec('.$r['recId'].',\'View\')"></i>';
                    }

                    echo '</td>';
                    echo '<td>'.str_pad($r['recId'], 10, "0", STR_PAD_LEFT).'</td>';
                    $date = date_create($r['recFecha']);                    
                    echo '<td style="text-align: center">'.date_format($date, 'd-m-Y').'</td>';
                    echo '<td style="text-align: left">'.$r['prvRazonSocial'].' - '.$r['prvApellido'].' '.$r['prvNombre'].'</td>';
                    echo '<td style="text-align: center">'.($r['recEstado'] == 'AC' ? '<small class="label bg-green">Activo</small>' : ($r['recEstado'] == 'DS' ? '<small class="label bg-red">Descartado</small>' : '<small class="label bg-blue">Confirmado</small>')).'</td>';
                    echo '</tr>';
                  }
                  
                }
              ?>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="8" style="text-align: center" id="footerRow">
                <?php 
                if($list['page'] == 1){
                  echo '<button type="button" class="btn btn-default disabled"><i class="fa fa-fw fa-backward"></i></button>';
                } else {
                  echo '<button type="button" class="btn btn-default" onclick="page('.($list['page'] - 1).')"><i class="fa fa-fw fa-backward"></i></button>';
                }

                echo '<span style="padding: 0px 15px">'.$list['page'].'   de   '.$list['totalPage'].'</span>';

                if($list['page'] == $list['totalPage']){
                  echo '<button type="button" class="btn btn-default disabled"><i class="fa fa-fw fa-forward"></i></button>';
                } else {
                  echo '<button type="button" class="btn btn-default" onclick="page('.($list['page'] + 1).')"><i class="fa fa-fw fa-forward"></i></button>';
                }
                ?>
                </td>
              </tr>
            </tfoot>
          </table>
        </div><!-- /.box-body -->

      </div><!-- /.box -->
    </div><!-- /.col -->
  </div><!-- /.row -->
</section><!-- /.content -->

<script>
$('#table_search').keyup(function(e) {
    var code = (e.keyCode ? e.keyCode : e.which);
    if (code==13) {
        e.preventDefault();
    }

    if (code == 32 || code == 13 || code == 188 || code == 186) {
        page(1);
    }
  });


  function page(p){
  WaitingOpen('Cargando...');
      $.ajax({
            type: 'POST',
            data: {
              page: p,
              txt: $('#table_search').val()
            },
            url: 'index.php/reception/pagination', 
            success: function(result){
                      WaitingClose();
                      $('#credit > tbody').html('');
                      $.each( result.data, function( key, value ) {
                        var row = '';
                        row += '<tr>';
                        row += '<td>';
                        if(value.recEstado == 'AC'){
                          row += '<i class="fa fa-fw fa-check" style="color: #00a65a; cursor: pointer; margin-left: 15px;" onclick="LoadRec('+value.recId+',\'Conf\')"></i>';
                          row += '<i class="fa fa-fw fa-ban" style="color: #dd4b39 ; cursor: pointer; margin-left: 15px;" onclick="LoadRec('+value.recId+',\'Disc\')"></i>';
                        }
                        row += '<i class="fa fa-fw fa-search" style="color: #3c8dbc; cursor: pointer; margin-left: 15px;" onclick="LoadRec('+value.recId+',\'View\')"></i>';
                        row += '</td>';
                        row += '<td>'+("000000000"+value.recId).slice(-10)+'</td>';
                        var date = new Date(value.recFecha);
                        row += '<td style="text-align: center">'+("0"+date.getDate()).slice(-2)+'-'+("0"+date.getMonth()).slice(-2)+'-'+("0"+date.getFullYear()).slice(-2)+'</td>';
                        row += '<td style="text-align: left">'+value.prvRazonSocial+' - '+value.prvNombre+' '+value.prvApellido+'</td>';
                        row += '<td style="text-align: center">'+(value.recEstado == 'AC' ? '<small class="label bg-green">Activo</small>' : (value.recEstado == 'DS' ? '<small class="label bg-red">Descartado</small>' : '<small class="label bg-blue">Confirmado</small>'))+'</td>';
                        row += '</tr>';
                        $('#credit > tbody').append(row);
                      });
                      
                      var foot = '';
                      if(result.page == 1){
                        foot += '<button type="button" class="btn btn-default disabled"><i class="fa fa-fw fa-backward"></i></button>';
                      } else {
                        foot += '<button type="button" class="btn btn-default" onclick="page('+(parseInt(result.page) - 1)+')"><i class="fa fa-fw fa-backward"></i></button>';
                      }

                      foot += '<span style="padding: 0px 15px">'+result.page+'   de   '+result.totalPage+'</span>';

                      if(result.page == result.totalPage){
                        foot += '<button type="button" class="btn btn-default disabled"><i class="fa fa-fw fa-forward"></i></button>';
                      } else {
                        foot += '<button type="button" class="btn btn-default" onclick="page('+(parseInt(result.page) + 1)+')"><i class="fa fa-fw fa-forward"></i></button>';
                      }
                      $('#footerRow').html(foot);
            },
            error: function(result){
              WaitingClose();
              alert("error");
            },
            dataType: 'json'
        });

  }

  
  var id = 0;
  var action = '';
  
  function LoadRec(id_, action_){
    id = id_;
    action = action_;
    LoadIconAction('modalAction',action);
    WaitingOpen('Cargando Recepción');
      $.ajax({
            type: 'POST',
            data: { id : id_, act: action },
        url: 'index.php/reception/getReception', 
        success: function(result){
                      WaitingClose();
                      $("#modalBodyReception").html(result.html);
                      $(".select2").select2();
                      $('#recFecha').datepicker({maxDate: '0'});
                      setTimeout("$('#modalReception').modal('show')",800);
              },
        error: function(result){
              WaitingClose();
              alert("error");
            },
            dataType: 'json'
        });
  }

  $('#btnSave').click(function(){
    
    if(action == 'View')
    {
      $('#modalReception').modal('hide');
      return;
    }

    var hayError = false;
    if($('#prvId').val() == '')
    {
      hayError = true;
    }

    if($('#recFecha').val() == '')
    {
      hayError = true;
    }

    var table = $('#saleDetail > tbody> tr');
    var rece = [];
    table.each(function(r) {
      var object = {
        artId:          parseInt(this.children[4].textContent),
        recCant:        parseInt(this.children[3].textContent)
      };

      rece.push(object);
    });

    if(rece.length <= 0)
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
                    id_ : id, 
                    act: action, 
                    prvId: $('#prvId').val(),
                    date: $('#recFecha').val(),
                    obsv: $('#recObservacion').val(),
                    rec: rece
                  },
        url: 'index.php/reception/setReception', 
        success: function(result){
                      WaitingClose();
                      $('#modalReception').modal('hide');
                      setTimeout("cargarView('reception', 'index', '"+$('#permission').val()+"');",1000);
              },
        error: function(result){
              WaitingClose();
              ProcesarError(result.responseText, 'modalReception');
            },
            dataType: 'json'
        });
  });

</script>


<!-- Modal -->
<div class="modal fade" id="modalReception" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="width: 50%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span id="modalAction"> </span> Recepción</h4> 
      </div>
      <div class="modal-body" id="modalBodyReception">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnSave">Guardar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalSearch" tabindex="3000" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="width: 50%">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel"><span id="modalAction__"> </span> Artículo</h4> 
      </div>
      <div class="modal-body" id="modalBodySearch">
        
        <div class="row">
          <div class="col-xs-10 col-xs-offset-1"><center>Producto</center></div>
        </div>
        <div class="row">
          <div class="col-xs-10 col-xs-offset-1">
            <input type="text" class="form-control" id="artIdSearch" value="" min="0">
          </div>
        </div><br>

        <div class="row">
          <div class="col-xs-10 col-xs-offset-1">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th width="1%"></th>
                  <th width="10%">Código</th>
                  <th>Descripción</th>
                </tr>
              </thead>
            </table>
            <table id="saleDetailSearch" style="height:20em; display:block; overflow: auto;" class="table table-bordered">
              <tbody>

              </tbody>
            </table>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" onclick="cancelarBusqueda()">Cancelar</button>
      </div>
    </div>
  </div>
</div>