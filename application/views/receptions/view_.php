<div class="row">
	<div class="col-xs-12">
		<div class="alert alert-danger alert-dismissable" id="error" style="display: none">
	        <h4><i class="icon fa fa-ban"></i> Error!</h4>
	        Revise que todos los campos esten completos
      </div>
	</div>
</div>

<div class="row">
	<div class="col-xs-4">
      <label style="margin-top: 7px;">Proveedor <strong style="color: #dd4b39">*</strong>: </label>
    </div>
	<div class="col-xs-5">
    <select class="form-control select2" id="prvId" style="width: 100%;" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?> >
      <?php 
        echo '<option value="-1" selected></option>';
        foreach ($data['providers'] as $p) {
          echo '<option value="'.$p['prvId'].'" '.($data['reception']['prvId'] == $p['prvId'] ? 'selected' : '').'>'.$p['prvRazonSocial'].' ('.$p['prvApellido'].' '.$p['prvNombre'].')</option>'; //data-balance="'.$c['balance'].'" data-address="'.$c['cliAddress'].'" data-dni="'.$c['cliDni'].'"
        }
      ?>
    </select>
    </div>
</div><br>

<div class="row">
	<div class="col-xs-4">
      <label style="margin-top: 7px;">Fecha <strong style="color: #dd4b39">*</strong>: </label>
    </div>
	<div class="col-xs-3">
      <input type="text" class="form-control" placeholder="dd-mm-aaaa" id="recFecha" value="<?php echo $data['reception']['recFecha'];?>" readonly="readonly" >
    </div>
</div><br>

<div class="row">
	<div class="col-xs-4">
      <label style="margin-top: 7px;">Observación <strong style="color: #dd4b39">*</strong>: </label>
    </div>
	<div class="col-xs-5">
      <input type="text" class="form-control" id="recObservacion" value="<?php echo $data['reception']['recObservacion'];?>" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?> >
    </div>
</div><br>

<div class="row">
  <div class="col-xs-12"><hr></div>
</div>

<div class="row">
  <div class="col-xs-4">
      <label style="margin-top: 7px;">Producto: </label>
    </div>
  <div class="col-xs-5">
      <input type="text" class="form-control" id="artId" value="" min="0" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?> >
    </div>
  <div class="col-xs-2">
      <input type="number" class="form-control" id="artCant" value="1" min="1" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?> >
    </div>
  <div class="col-xs-1">
      <button type="button" class="btn btn-success" id="btnAddProd"><i class="fa fa-check"></i></button>
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
          <th width="10%">Cantidad</th>
        </tr>
      </thead>
    </table>
    <table id="saleDetail" style="height:20em; display:block; overflow: auto;" class="table table-bordered">
      <tbody>
      <?php
        foreach ($data['articles'] as $a) {
          echo '<tr>';
          echo '<td width="1%"></td>';
          echo '<td width="10%">'.$a['artBarCode'].'</td>';
          echo '<td>'.$a['artDescription'].'</td>';
          echo '<td width="10%" style="text-align: right">'.$a['recdCant'].'</td>';
          echo '<td style="display:none">'.$a['artId'].'</td>';
          echo '</tr>';
        }
      ?>
      </tbody>
    </table>
  </div>
</div>

<script>
  var isOpenWindow = false;
  $('#artId').keyup(function(e){
    var code = e.which;
    if(code==13){
      e.preventDefault();
      Buscar();
    }
  });

  $('#recObservacion').keyup(function(e){
    var code = e.which;
    if(code==13){
      e.preventDefault();
      $('#artId').focus();
    }
  });

  $('#artCant').keyup(function(e){
    var code = e.which;
    if(code==13){
      e.preventDefault();
      $('#btnAddProd').focus();
    }
  });

  var idSale = $('#saleDetail > tbody').find('tr').length+1;
  function Buscar(){

    WaitingOpen('Buscando');
    $.ajax({
          type: 'POST',
          data: { code: $('#artId').val() },
          url: 'index.php/article/searchByCode',
          success: function(result){
                        if(result != false){
                          var selected = $('#lpId').find('option:selected');
                          var margin = parseFloat(selected.data('porcent'));
                          //calcular precio de venta
                          var pVenta = parseFloat(result.pVenta);
                          if(margin > 0){
                            //console.debug(" =>>> %o * ( %o ) / 100: ",pVenta,margin,(pVenta * (margin / 100)));
                            pVenta += pVenta * (margin / 100);
                          }
                          WaitingClose();
                          var cantidad = parseFloat($('#artCant').val() == '' ? 1 : $('#artCant').val());
                          var row = '<tr id="'+idSale+'">';
                          row += '<td width="1%"><i class="fa fa-fw fa-times-circle" style="color: #dd4b39; cursor: pointer;" onclick="delete_('+idSale+')"></i></td>';
                          row += '<td width="10%">'+result.artBarCode+'</td>';
                          row += '<td>'+result.artDescription+'</td>';
                          row += '<td width="10%" class="td_cant" style="text-align: right"   data-pventa="'+pVenta+'"  >'+cantidad+'</td>';
                          /*
                          row += '<td width="10%" class="td_pventa"  style="text-align: right" >'+parseFloat(pVenta).toFixed(2)+'</td>';
                          row += '<td width="10%" class="td_total" style="text-align: right">'+(parseFloat(pVenta) * parseFloat(cantidad)).toFixed(2)+'</td>';
                          */
                          row += '<td style="display: none">'+result.artId+'</td>';
                          row += '<td style="display: none">'+result.pVenta+'</td>';
                          row += '<td style="display: none">'+result.artCoste+'</td>';
                          row += '</tr>';
                          $('#saleDetail > tbody').prepend(row);
                          idSale++;

                          $('#artCant').val('1');
                          $('#artId').val('');
                          Calcular();
                          $('#artId').focus();
                        } else {
                          AbrirBuscador();
                        }
                },
          error: function(result){
                WaitingClose();
                ProcesarError(result.responseText, 'modalReception');
              },
              dataType: 'json'
      });
    //---------------
  }

  $('#btnAddProd').click(function(){
    Buscar();
  });

  function delete_(id){
    $('#'+id).remove();
    //Calcular();
    $('#artId').focus();
  }

  function AbrirBuscador(){
    LoadIconAction('modalAction__','Search');
    WaitingClose();
    //$('#modalReception').modal('hide');
    cerro();
    $('#modalSearch').modal({ backdrop: 'static', keyboard: false });
    $('#artIdSearch').val($('#artId').val());
    $('#saleDetailSearch > tbody').html('');
    $('#modalSearch').modal('show');
    setTimeout(function () { $('#artIdSearch').focus(); BuscarCompleto();}, 1000);
  }

  function cancelarBusqueda(){
    $('#modalSearch').modal('hide');
    //$('#modalReception').modal('show');
    isOpenWindow = true;
    $('#artCant').val('1');
    $('#artId').val('');
    //setTimeout(function () { $('#artId').focus(); }, 8000);
    $('#artId').focus();
  }

  function cerro(){
    isOpenWindow = false;
  }

  var timer, timeout = 1000;
  var row = 0, rows = 0;
  var move = 0;

    function BuscarCompleto(){
    $('#saleDetailSearch > tbody').html('');
    row = 0;
    rows = 0;
    if($('#artIdSearch').val().length >= 3){
      $.ajax({
            type: 'POST',
            data: { code: $('#artIdSearch').val() },
            url: 'index.php/article/searchByAll',
            success: function(resultList){
                          if(resultList != false){
                            $.each(resultList, function(index, result){
                              if(result.artEstado == 'AC'){
                                var row = '<tr>';
                                row += '<td width="10%"><i style="color: #00a65a; cursor: pointer;" class="fa fa-fw fa-check-square"';
                                row += 'onClick="agregar(\''+result.artDescription+'\')"></i></td>';
                                row += '<td width="20%">('+result.artBarCode+')</td>';
                                row += '<td>'+result.artDescription+'</td>';
                                row += '<td width="20%" style="text-align: right"><b> $ '+parseFloat(result.pVenta).toFixed(2)+'</b></td>';
                                row += '<td width="10%" style="text-align: right">'+(result.stock == null ? 0 : result.stock)+'</td>';
                                row += '</tr>';
                                $('#saleDetailSearch > tbody').prepend(row);
                                rows++;
                              }
                            });
                            $('#artIdSearch').focus();
                          }
                          $("#loadingIcon").hide();
                  },
            error: function(result){
                  $("#loadingIcon").hide();
                  ProcesarError(result.responseText, 'modalReception');
                },
                dataType: 'json'
        });
    }
  }

  $('#artIdSearch').keyup(function(e){
      var code = e.which;
      if(code != 40 && code != 38 && code != 13){
        if($('#artIdSearch').val().length >= 3){
          // Clear timer if it's set.
          if (typeof timer != undefined)
            clearTimeout(timer);

          // Set status to show we're typing.
          //$("#status").html("Typing ...").css("color", "#009900");
          
          
          timer = setTimeout(function()
          {
            //$("#status").html("Stopped").css("color", "#990000");
            $("#loadingIcon").show();
            BuscarCompleto();
            row = 0;
          }, timeout);
        }
      } else {
        var removeStyle = $("#saleDetailSearch > tbody tr:nth-child("+row+")");
        if(code == 13){//Seleccionado
          removeStyle.css('background-color', 'white');
          agregar($('#saleDetailSearch tbody tr:nth-child('+row+') td:nth-child(3)').text());
        }

        if(code == 40){//abajo
          if((row + 1) <= rows){
            row++;
            if(row > 5){
            }
            removeStyle.css('background-color', 'white');
          }
          var rowE = $("#saleDetailSearch > tbody tr:nth-child("+row+")");
          rowE.css('background-color', '#D8D8D8');
          animate();
        } 
        if(code == 38) {//arriba
          if(row >= 2){
            row--;
            removeStyle.css('background-color', 'white');
          }
          var rowE = $("#saleDetailSearch > tbody tr:nth-child("+row+")");
          rowE.css('background-color', '#D8D8D8');
          animate();
        }
      }
    });

  function animate() {  
       //En base a la altura del tr hacer el move (- para cuando hago abajo / + para cuando hago para arriba)
      //siempre que la cantidad de filas sea mayor a 350px, que es el alto del buscador 
      //$('#saleDetailSearch').animate({ "margin-top": move + "px" }, 1);
  }

  function agregar(barCode){
    //debugger;
    $('#artId').val(barCode);
    $('#modalSearch').modal('hide');
    //$('#modalReception').modal('show');
    isOpenWindow = true;
    setTimeout(function () { $('#artCant').focus(); $('#artCant').select(); }, 800);
  }
/*
  $('#btnSave').click(function(){
    
    if(acMachine == 'View')
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
*/

  //var idSale = 0 ;
  /*
  function Buscar(){
    WaitingOpen('Buscando');
    $.ajax({
          type: 'POST',
          data: { code: $('#artId').val() },
          url: 'index.php/article/searchByCode', 
          success: function(result){
                        if(result != false){
                          WaitingClose();
                          var cantidad = parseInt($('#artCant').val() == '' ? 1 : $('#artCant').val());
                          var row = '<tr id="'+idSale+'">';
                          row += '<td width="1%"><i class="fa fa-fw fa-times-circle" style="color: #dd4b39; cursor: pointer;" onclick="delete_('+idSale+')"></i></td>';
                          row += '<td width="10%">'+result.artBarCode+'</td>';
                          row += '<td>'+result.artDescription+'</td>';
                          row += '<td width="10%" style="text-align: right">'+cantidad+'</td>';
                          row += '<td style="display: none">'+result.artId+'</td>';
                          row += '</tr>';
                          $('#saleDetail > tbody').prepend(row);
                          idSale++;

                          $('#artCant').val('1');
                          $('#artId').val('');
                          //Calcular();
                          $('#artId').focus();
                        } else {
                          AbrirBuscador();
                        }
                },
          error: function(result){
                WaitingClose();
                ProcesarError(result.responseText, 'modalReception');
              },
              dataType: 'json'
      });
    //---------------
  }
  */
  /*
  function cancelarBusqueda(){
    $('#modalSearch').modal('hide');
    $('#modalReception').modal('show');
    isOpenWindow = true;
    $('#artCant').val('1');
    $('#artId').val('');
    setTimeout(function () { $('#artId').focus(); }, 1000);
  }

  function cerro(){
    isOpenWindow = false;
  }
  */
/*
    function BuscarCompleto(){
    $('#saleDetailSearch > tbody').html('');
    $.ajax({
          type: 'POST',
          data: { code: $('#artIdSearch').val() },
          url: 'index.php/article/searchByAll', 
          success: function(resultList){
                        if(resultList != false){
                          WaitingClose();
                          $.each(resultList, function(index, result){
                            if(result.artEstado == 'AC'){
                              var row = '<tr>';
                              row += '<td width="1%"><i style="color: #00a65a; cursor: pointer;" class="fa fa-fw fa-check-square"';
                              row += 'onClick="agregar('+result.artBarCode+')"></i></td>';
                              row += '<td width="10%">'+result.artBarCode+'</td>';
                              row += '<td>'+result.artDescription+'</td>';
                              row += '</tr>';
                              $('#saleDetailSearch > tbody').prepend(row);
                            }
                          });
                          $('#artIdSearch').focus();
                        }
                },
          error: function(result){
                WaitingClose();
                ProcesarError(result.responseText, 'modalSale');
              },
              dataType: 'json'
      });
  }

  $('#artIdSearch').keyup(function(){
    BuscarCompleto();
  });

  function agregar(barCode){
    $('#artId').val(barCode);
    $('#modalSearch').modal('hide');
    $('#modalReception').modal('show');
    isOpenWindow = true;
    setTimeout(function () { $('#artId').focus(); }, 1000);
  }
  */
</script>