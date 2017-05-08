<input type="hidden" id="permission" value="<?php echo $permission;?>">
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Cajas</h3>
          <?php
          if (strpos($permission,'Add') !== false) {
            if($list['openBox'] == 0) {
              echo '<button class="btn btn-block btn-success" style="width: 100px; margin-top: 10px;" data-toggle="modal" onclick="LoadBox(0,\'Add\')" id="btnAdd" title="Nueva">Abrir</button>';
            } else {
              echo '<button class="btn btn-block btn-success" style="width: 100px; margin-top: 10px;" data-toggle="modal" id="btnAdd" title="Nueva" disabled="disabled">Abrir</button>';
            }
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
                <th>Apertura</th>
                <th>Cierre</th>
                <th>Usuario</th>
                <!--
                <th>Debe</th>
                <th>Haber</th>
                -->
              </tr>
            </thead>
            <tbody>
              <?php
                if(count($list['data']) > 0) {                  
                  foreach($list['data'] as $c)
                  {
                    echo '<tr>';
                    echo '<td>';

                    if (strpos($permission,'Close') !== false) {
                      if($c['cajaCierre'] == null){
                        echo '<i class="fa fa-fw fa-lock" style="color: #00a65a; cursor: pointer; margin-left: 15px;" onclick="LoadBox('.$c['cajaId'].',\'Close\')"></i>';
                      }
                    }
                    
                    if (strpos($permission,'View') !== false) {
                      echo '<i class="fa fa-fw fa-search" style="color: #3c8dbc; cursor: pointer; margin-left: 15px;" onclick="LoadBox('.$c['cajaId'].',\'View\')"></i>';
                    }
                    echo '</td>';
                    echo '<td>'.str_pad($c['cajaId'], 10, "0", STR_PAD_LEFT).'</td>';
                    $date = date_create($c['cajaApertura']);                    
                    echo '<td style="text-align: center">'.date_format($date, 'd-m-Y H:i').'</td>';
                    if($c['cajaCierre'] != null)
                    {
                      $date = date_create($c['cajaCierre']);
                      echo '<td style="text-align: center">'.date_format($date, 'd-m-Y H:i').'</td>';
                    } else { echo '<td style="text-align: center">-</td>'; }
                    echo '<td style="text-align: left">'.$c['usrName'].', '.$c['usrLastName'].'</td>';
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
            url: 'index.php/box/pagination', 
            success: function(result){
                      WaitingClose();
                      $('#credit > tbody').html('');
                      $.each( result.data, function( key, value ) {
                        var row = '';
                        row += '<tr>';
                        row += '<td>';
                        row += '<i class="fa fa-fw fa-lock" style="color: #00a65a; cursor: pointer; margin-left: 15px;" onclick="LoadCtaCte('+value.cajaId+',\'Close\')"></i>';
                        row += '<i class="fa fa-fw fa-search" style="color: #3c8dbc; cursor: pointer; margin-left: 15px;" onclick="LoadCtaCte('+value.cajaId+',\'View\')"></i>';
                        row += '</td>';
                        row += '<td>'+("000000000"+value.cajaId).slice(-10)+'</td>';
                        var date = new Date(value.cajaApertura);
                        row += '<td style="text-align: center">'+("0"+date.getDate()).slice(-2)+'-'+("0"+date.getMonth()).slice(-2)+'-'+("0"+date.getFullYear()).slice(-2)+' '+("0"+date.getHours()).slice(-2)+':'+("0"+date.getMinutes()).slice(-2)+'</td>';
                        if(value.cajaCierre != null) {
                          var date = new Date(value.cajaCierre);
                          row += '<td style="text-align: center">'+("0"+date.getDate()).slice(-2)+'-'+("0"+date.getMonth()).slice(-2)+'-'+("0"+date.getFullYear()).slice(-2)+' '+("0"+date.getHours()).slice(-2)+':'+("0"+date.getMinutes()).slice(-2)+'</td>';
                        } else { row += '<td style="text-align: center">-</td>'; }
                        row += '<td style="text-align: left">'+value.usrName+', '+value.usrLastName+'</td>';
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
  
  function LoadBox(id_, action_){
  	id = id_;
  	action = action_;
  	LoadIconAction('modalAction',action);
  	WaitingOpen('Cargando Caja');
      $.ajax({
          	type: 'POST',
          	data: { id : id_, act: action },
    		url: 'index.php/box/getBox', 
    		success: function(result){
			                WaitingClose();
			                $("#modalBodyBox").html(result.html);
                      $("#cajaImpApertura").maskMoney({allowNegative: false, thousands:'', decimal:'.'});
                      $("#cajaImpRendicion").maskMoney({allowNegative: false, thousands:'', decimal:'.'});
			                setTimeout("$('#modalBox').modal('show')",800);
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
  		$('#modalBox').modal('hide');
  		return;
  	}

  	var hayError = false;
    {
      switch (action){
        case 'Add':
          if($('#cajaImpApertura').val() == '')
          {
            hayError = true;
          }
          break;

        case 'Close':
          if($('#cajaImpRendicion').val() == '')
          {
            hayError = true;
          }
          break;

        default: hayError = true;
      }
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
                    id : id, 
                    act: action, 
                    ape: $('#cajaImpApertura').val(),
                    ven: $('#cajaImpVentas').val(),
                    cie: $('#cajaImpRendicion').val()
                  },
    		url: 'index.php/box/setBox', 
    		success: function(result){
                			WaitingClose();
                			$('#modalBox').modal('hide');
                			setTimeout("cargarView('box', 'index', '"+$('#permission').val()+"');",1000);
    					},
    		error: function(result){
    					WaitingClose();
    					alert("error");
    				},
          	dataType: 'json'
    		});
  });

</script>


<!-- Modal -->
<div class="modal fade" id="modalBox" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span id="modalAction"> </span> Caja</h4> 
      </div>
      <div class="modal-body" id="modalBodyBox">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnSave">Guardar</button>
      </div>
    </div>
  </div>
</div>