<input type="hidden" id="permission" value="<?php echo $permission; ?>">
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Cobranza</h3>
          <?php
          if (strpos($permission,'Add') !== false) {
            if($list['openBox'] == 1) {
              echo '<button class="btn btn-block btn-success" style="width: 100px; margin-top: 10px;" data-toggle="modal" id="btnAdd" title="Agregar">Agregar (F9)</button>';
            } else {
              echo '<button class="btn btn-block btn-success" style="width: 100px; margin-top: 10px;" data-toggle="modal" title="Agregar" disabled="disabled">Agregar (F9)</button>';
            }
          }
          ?>
        </div><!-- /.box-header -->
        <div class="box-body">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <?php
                if (strpos($permission,'Cob') !== false) {
                  echo '<li><a href="#tab_1" data-toggle="tab" onClick="load(1)">Ordenes de Compra</a></li>';
                }

                if (strpos($permission,'Anu') !== false) {
                  echo '<li><a href="#tab_2" data-toggle="tab" onClick="load(2)">Facturación</a></li>';
                }

                if (strpos($permission,'AyC') !== false) {
                  echo '<li><a href="#tab_2" data-toggle="tab" onClick="load(3)">Caja</a></li>';
                }
              ?>
              
            </ul>
            <div class="tab-content" id="contentTab">

            </div>
            <!--
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
              </div>
              <div class="tab-pane active" id="tab_2">
              </div>
              <div class="tab-pane active" id="tab_3">
              </div>
            </div>
            -->
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div><!-- /.col -->
  </div><!-- /.row -->
</section><!-- /.content -->

<script>
function load(idTab){
  idRubro = id_;
  acRubro = action;
  WaitingOpen('Cargando ...');
    $.ajax({
          type: 'POST',
          data: { id : idTab},
      url: 'index.php/sale/getTabContent', 
      success: function(result){
                    $("#contentTab").html(result);
                    WaitingClose();
            },
      error: function(result){
            WaitingClose();
            alert(result);
            //ProcesarError(result.responseText, 'modalRubro');
          },
          dataType: 'json'
      });
}

var isOpenWindow = false;
  $(function () {
    $('#sales').DataTable({
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

    $(document).keyup(function(event) {
      // open  pressing "F9"
      if (event.keyCode === 120) {
        OpenSale();
      }

      // open  pressing "F10"
      if (event.keyCode === 121) {
        $('#pagaConLabel').hide();
        $('#pagaConInput').show();
        $('#pagaConInput').focus();
      }

      // facturar pressing "F7"
      if (event.keyCode === 118) {
        Cobrar();
      }

    });
    function OpenSale(){
      var btn = $('#btnAdd');
      if(btn.is(':enabled')){
        //Abrir ventana de facturación
        if(isOpenWindow == false){
          isOpenWindow = true;
          LoadIconAction('modalActionSale','Add');
          WaitingOpen('Cargando...');
          $('#modalSale').modal({ backdrop: 'static', keyboard: false });
          $('#modalSale').modal('show');
          setTimeout(function () { $('#artId').focus(); }, 1000);
          $('#saleDetail > tbody').html('');
          Calcular();
          WaitingClose();
          $("#pagaConInput").maskMoney({allowNegative: false, thousands:'', decimal:'.'});
        }
      }
    }

    $('#btnSave').click(function(){
       Cobrar();
    });

    $('#btnAdd').click(function(){
       OpenSale();
    });
    
    function Cobrar(){
      var items = parseInt($('#saleItems').html());
      var venta = parseFloat($('#saleTotal').html());

      if(items > 0 && venta > 0){
        var table = $('#saleDetail > tbody> tr');
        var sale = [];
        table.each(function(r) {
          var object = {
            artId:          parseInt(this.children[7].textContent),
            artCode:        this.children[1].textContent,
            artDescription: this.children[2].textContent,
            artCoste:       parseFloat(this.children[6].textContent),
            artFinal:       parseFloat(this.children[3].textContent),
            venCant:        parseInt(this.children[4].textContent)
          };

          sale.push(object);
        });

        WaitingOpen('Buscando');
        $.ajax({
              type: 'POST',
              data: { articles: sale },
              url: 'index.php/sale/setSale', 
              success: function(result){
                      WaitingClose();
                      $('#modalSale').modal('hide');
                      setTimeout("cargarView('Sale', 'index', '"+$('#permission').val()+"');",1000);
                    },
              error: function(result){
                    WaitingClose();
                    ProcesarError(result.responseText, 'modalSale');
                  },
                  dataType: 'json'
          });
      }
    }

    $('#pagaConInput').keyup(function(e){ 
      var code = e.which; 
      if(code==13)e.preventDefault();
      if(code==13){
          $('#pagaConInput').hide();
          $('#pagaConLabel').show();

          $('#pagaConLabel').html(parseFloat($('#pagaConInput').val()).toFixed(2));
          $('#artId').focus();

          Calcular();
          $('#pagaConInput').val('0');
        } 
        if(code==27){
          $('#pagaConInput').val('0');
          $('#pagaConInput').hide();
          $('#pagaConLabel').show();

          $('#pagaConLabel').html('0.00');
          $('#artId').focus();
          Calcular();
        }
    });

    $('#artId').keyup(function(e){ 
      var code = e.which; 
      if(code==13)e.preventDefault();
      if(code==32||code==13||code==188||code==186){
          //Buscar articulo
          Buscar();
        }
    });

    $('#artIdSearch').keyup(function(){
      BuscarCompleto();
    });

  });

  $('#btnAddProd').click(function(){
    Buscar();
  });

  var idSale = 0 ;
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
                          row += '<td width="10%" style="text-align: right">'+parseFloat(result.pVenta).toFixed(2)+'</td>';
                          row += '<td width="10%" style="text-align: right">'+cantidad+'</td>';
                          row += '<td width="10%" style="text-align: right">'+parseFloat(cantidad * result.pVenta).toFixed(2)+'</td>';
                          row += '<td style="display: none">'+result.artCoste+'</td>';
                          row += '<td style="display: none">'+result.artId+'</td>';
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
                ProcesarError(result.responseText, 'modalSale');
              },
              dataType: 'json'
      });
    //---------------
  }

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
                              row += '<td width="10%" style="text-align: right">'+parseFloat(result.pVenta).toFixed(2)+'</td>';
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

  function AbrirBuscador(){
    LoadIconAction('modalAction','Search');
    WaitingClose();
    $('#modalSale').modal('hide');
    cerro();
    $('#modalSearch').modal({ backdrop: 'static', keyboard: false });
    $('#modalSearch').modal('show');
    setTimeout(function () { $('#artIdSearch').focus(); }, 1000);
  }

  function delete_(id){
    $('#'+id).remove();
    Calcular();
    $('#artId').focus();
  }

  function agregar(barCode){
    $('#artId').val(barCode);
    $('#modalSearch').modal('hide');
    $('#modalSale').modal('show');
    isOpenWindow = true;
    setTimeout(function () { $('#artId').focus(); }, 1000);
  }

  function Calcular(){
    var table = $('#saleDetail > tbody> tr');

    var items = 0;
    var total = 0;
    table.each(function(r) {
      items += parseInt(this.children[4].textContent);
      total += parseFloat(this.children[5].textContent);
    });

    $('#saleItems').html(items);
    $('#saleTotal').html(parseFloat(total).toFixed(2));

    var pagaCon = parseFloat($('#pagaConLabel').html()).toFixed(2);
    if(pagaCon > total ){
      $('#vueltoLabel').html(parseFloat(pagaCon - total).toFixed(2));
    }else{
      $('#vueltoLabel').html('0.00');
    }
  }

  function cancelarBusqueda(){
    $('#modalSearch').modal('hide');
    $('#modalSale').modal('show');
    isOpenWindow = true;
    $('#artCant').val('1');
    $('#artId').val('');
    setTimeout(function () { $('#artId').focus(); }, 1000);
  }

  function cerro(){
    isOpenWindow = false;
  }
  
  var id_ = 0;
  var action = '';
  
  function LoadSale(id__, action_){
  	id_ = id__;
  	action = action_;
  	LoadIconAction('modalAction_',action);
    WaitingOpen('Cargando Venta');
      $.ajax({
          	type: 'POST',
          	data: { id : id_, act: action_ },
    		url: 'index.php/sale/getSale', 
    		success: function(result){
			                WaitingClose();
			                $("#modalBodySale_").html(result.html);
                      $('#madPrecio').maskMoney({
                          precision: 3
                      });
                      $('#madPrecioPulgada').maskMoney({
                          precision: 3
                      });
			                setTimeout("$('#modalSale_').modal('show')",800);
    					},
    		error: function(result){
    					WaitingClose();
    					ProcesarError(result.responseText, 'modalSale_');
    				},
          	dataType: 'json'
    		});
  }

  $('#btnSave_').click(function(){
  	
  	if(action == 'View')
  	{
  		$('#modalSale_').modal('hide');
  		return;
  	}

    WaitingOpen('Eliminando factura');
    	$.ajax({
          	type: 'POST',
          	data: { 
                    id : id_
                  },
    		url: 'index.php/sale/delSale', 
    		success: function(result){
                			WaitingClose();
                			$('#modalSale_').modal('hide');
                			setTimeout("cargarView('sale', 'index', '"+$('#permission').val()+"');",1000);
    					},
    		error: function(result){
    					WaitingClose();
    					ProcesarError(result.responseText, 'modalSale_');
    				},
          	dataType: 'json'
    		});
  });


  function Print(id__){
    WaitingOpen('Generando reporte...');
    LoadIconAction('modalAction__','Print');
    $.ajax({
            type: 'POST',
            data: { 
                    id : id__
                  },
        url: 'index.php/sale/printSale', 
        success: function(result){
                      WaitingClose();
                      var url = "./assets/reports/" + result;
                      $('#printDoc').attr('src', url);
                      setTimeout("$('#modalPrint').modal('show')",800);
              },
        error: function(result){
              WaitingClose();
              ProcesarError(result.responseText, 'modalPrint');
            },
            dataType: 'json'
        });
  }

</script>


<!-- Modal -->
<div class="modal fade" id="modalSearch" tabindex="3000" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="width: 80%">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel"><span id="modalAction"> </span> Artículo</h4> 
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
                  <th width="10%">P.Unitario</th>
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

<!-- Modal -->
<!--
<div class="modal fade" id="modalSale" tabindex="2000" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="width: 80%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="cerro()"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span id="modalActionSale"> </span> Venta</h4> 
      </div>
      <div class="modal-body" id="modalBodySale">
        
        <div class="row">
          <div class="col-xs-2 col-xs-offset-1"><center>Cantidad</center></div>
          <div class="col-xs-8"><center>Producto</center></div>
        </div>
        <div class="row">
          <div class="col-xs-2 col-xs-offset-1">
            <input type="number" class="form-control" id="artCant" value="1" min="1">
          </div>
          <div class="col-xs-7">
            <input type="number" class="form-control" id="artId" value="" min="0">
          </div>
          <div class="col-xs-2"><button type="button" class="btn btn-success" id="btnAddProd"><i class="fa fa-check"></i></button></div>
        </div><br>

        <div class="row">
          <div class="col-xs-10 col-xs-offset-1">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th width="35px"></th>
                  <th width="10%">Código</th>
                  <th>Descripción</th>
                  <th width="10%">P.Unitario</th>
                  <th width="10%">Cantidad</th>
                  <th width="10%">Total</th>
                </tr>
              </thead>
            </table>
            <table id="saleDetail" style="height:20em; display:block; overflow: auto;" class="table table-bordered">
              <tbody>

              </tbody>
            </table>
          </div>
        </div>

        <div class="row">
          <div class="col-xs-1 col-xs-offset-1">
              <label style="font-size: 20px; margin-top: 10px;" id="saleItems">0</label>
          </div>

          <div class="col-xs-2 col-xs-offset-4">
              <label style="font-size: 20px; margin-top: 10px;">Total</label>
          </div>
          <div class="col-xs-3 text-right">
              <label style="font-size: 30px; color: red;" id="saleTotal">0.00</label>
          </div>
        </div>

        <div class="row">
          <div class="col-xs-2 col-xs-offset-6">
              <label style="font-size: 20px; margin-top: 10px;">Paga con (F10)</label>
          </div>
          <div class="col-xs-3 text-right">
              <label style="font-size: 30px; color: green;" id="pagaConLabel">0.00</label>
              <input type="text" class="form-control" value="0.00" style="font-size: 30px; display: none; text-align: right;" id="pagaConInput">
          </div>
        </div>

        <div class="row">
          <div class="col-xs-2 col-xs-offset-6">
              <label style="font-size: 20px; margin-top: 10px;">Su Vuelto</label>
          </div>
          <div class="col-xs-3 text-right">
              <label style="font-size: 30px; color: blue;" id="vueltoLabel">0.00</label>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="cerro()">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnSave">Guardar</button>
      </div>
    </div>
  </div>
</div>
-->

<!-- Modal -->
<div class="modal fade" id="modalSale_" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="width: 50%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel_"><span id="modalAction_"> </span> Venta</h4> 
      </div>
      <div class="modal-body" id="modalBodySale_">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnSave_">Guardar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalPrint" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="width: 50%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel__"><span id="modalAction__"> </span> Comprobante</h4> 
      </div>
      <div class="modal-body" id="modalBodyPrint">
        <div>
          <iframe style="width: 100%; height: 600px;" id="printDoc" src=""></iframe>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <!--<button type="button" class="btn btn-primary" id="btnSave_">Guardar</button>-->
      </div>
    </div>
  </div>
</div>