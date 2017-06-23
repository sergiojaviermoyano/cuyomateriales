
<div class="row">
	<div class="col-xs-12">
		<div class="alert alert-danger alert-dismissable" id="error" style="display: none">
	        <h4><i class="icon fa fa-ban"></i> Error!</h4>
          <p>Revise que todos los campos esten completos<br></p>

      </div>
	</div>
</div>

	<div class="row">
	 <label class="col-sm-3">Lista de Precios <strong style="color: #dd4b39">*</strong>:  </label>
	  <div class="col-sm-9">
<?php
if($data['act'] == 'Add' || $data['act'] == 'Pre' ){ ?>
	    <select class="form-control" id="lpId" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>>
	      <option value="-1" data-porcent="0">Lista de Precios</option>
	      <?php foreach ($ListaPrecios as $key => $item):?>
	        <option value="<?php echo $item['lpId'];?>" data-porcent="<?php echo $item['lpMargen'];?>" <?php echo $item['lpDefault'] == true ?'selected':''?> ><?php echo $item['lpDescripcion'];?> </option>
	      <?php endforeach;?>
	    </select>
<?php
} else { ?>
		<select class="form-control" id="lpId"<?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>>
	      <option value="-1" data-porcent="0">Lista de Precios</option>
	      <?php foreach ($ListaPrecios as $key => $item):?>
	        <option value="<?php echo $item['lpId'];?>" data-porcent="<?php echo $item['lpMargen'];?>" <?php echo $item['lpId'] == $data['order']['lpId'] ?'selected':''?> ><?php echo $item['lpDescripcion'];?> </option>
	      <?php endforeach;?>
	    </select>
<?php
}
?>
	   </div>
	</div><br>


<div class="row" style="display:none">
  <label class="col-sm-3">Cliente <strong style="color: #dd4b39">*</strong>:  </label>
  <div class="col-sm-9">
    <select class="form-control" id="cliId" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>>
      <?php foreach ($Clientes as $key => $item):?>
        <option value="<?php echo $item['cliId'];?>" <?php echo $item['cliDefault'] == true ?'selected':''?> ><?php echo $item['cliApellido'].' '.$item['cliNombre'];?> </option>
      <?php endforeach;?>
    </select>
  </div>
</div>

<div class="row">
  <label class="col-sm-3">Observación <strong style="color: #dd4b39">*</strong>: </label>
  <div class="col-sm-9">
    <input type="text" class="form-control" id="ocObservacion" value="<?php echo $data['order']['ocObservacion'] ?>"/>
  </div>
</div>

<hr>

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

    <table id="order_detail" class="table table-bordered table-hover">
      <thead>
        <tr>
          <th width="1%"></th>
          <th width="1%">Código</th>
          <th>Descripcion</th>
          <th width="1%">Cantidad</th>
          <th width="1%">P.Venta</th>
          <th class="text-center">Total</th>
        </tr>
      </thead>
      <tbody>
				<?php $cant_total=0?>
				<?php foreach ($data['detalleCompra'] as $key => $value):?>
					<?php $cant_total+=(int)$value['ocdCantidad']?>

					<tr id="<?php echo $key+1;?>">
						<td width="1%"><i style="color: #dd4b39; cursor: pointer;" class="fa fa-fw fa-times-circle" onClick="delete_(<?php echo $key+1;?>)"></i></td>
						<td width="10%"><?php echo $value['artBarCode']?></td>
						<td><?php echo $value['artDescripcion']?></td>
						<td width="10%" class="td_cant" style="text-align: right"   data-pventa="<?php echo $value['artPVenta']?>" ><?php echo  (float)$value['ocdCantidad']?></td>
						<td width="10%" class="td_pventa"  style="text-align: right" ><?php echo $value['artPVenta']?></td>
						<td width="10%" class="td_total" style="text-align: right"></td>
						<td style="display: none"><?php echo $value['artId']?></td>
						<td style="display: none"><?php echo $value['artPVenta']?></td>
						<td style="display: none"><?php echo $value['artPCosto']?></td>
					</tr>
				<?php endforeach;?>
      </tbody>
    </table>

<div class="row">
  <div class="col-xs-1 col-xs-offset-1">
      <label style="font-size: 20px; margin-top: 10px;" id="saleItems"><?php echo $cant_total;?></label>
  </div>

  <div class="col-xs-2 col-xs-offset-4">
			<label style="font-size: 15px; margin-top: 10px;">Redondeo:</label>
			<br>
      <label style="font-size: 20px; margin-top: 10px;">Total</label>
  </div>
  <div class="col-xs-3 text-right">
      <label style="font-size: 15px; color: red;" id="label_discount">0.00</label><br>
			<label style="font-size: 30px; color: red;" id="saleTotal">0.00</label>
			<input type="hidden" name="redondeo" id="redondeo" value="<?php echo $data['order']['redondeo'];?>">
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

  $('#ocObservacion').keyup(function(e){
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

var idSale = $('#order_detail > tbody').find('tr').length+1;
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
                          row += '<td width="10%" class="td_pventa"  style="text-align: right" >'+parseFloat(pVenta).toFixed(2)+'</td>';
                          row += '<td width="10%" class="td_total" style="text-align: right">'+(parseFloat(pVenta) * parseFloat(cantidad)).toFixed(2)+'</td>';
                          row += '<td style="display: none">'+result.artId+'</td>';
                          row += '<td style="display: none">'+result.pVenta+'</td>';
                          row += '<td style="display: none">'+result.artCoste+'</td>';
                          row += '</tr>';
                          $('#order_detail > tbody').prepend(row);
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
                ProcesarError(result.responseText, 'modalOrder');
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
    Calcular();
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
    $('#modalReception').modal('show');
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
                  ProcesarError(result.responseText, 'modalSale');
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
    $('#modalReception').modal('show');
    isOpenWindow = true;
    setTimeout(function () { $('#artCant').focus(); $('#artCant').select(); }, 800);
  }


	$(function(){
		$('#lpId').on('change',function(){
      //debugger;
			var selected = $('#lpId').find('option:selected');
			var margin = parseFloat(selected.data('porcent'));
			var td_cant=$("table").find("td.td_cant");
			var td_pventa=$("table").find("td.td_pventa");
			var td_total=$("table").find("td.td_total");
			var total=0;
			$.each(td_cant,function(index,item){
				var cantidad=parseFloat($(item).text());
				var pVenta = $(item).data('pventa');
				pVenta=parseFloat(pVenta);
				if(margin > 0){
					pVenta += pVenta * (margin / 100);
				}

        if(margin <0){
          margin  *= -1;
          pVenta -= pVenta * (margin / 100);
        }
				console.debug(")))> pVenta: %o",pVenta);
				var sub_total=(parseFloat(pVenta) * parseFloat(cantidad)).toFixed(2);
				$(td_pventa[index]).text(pVenta.toFixed(2));
        $(td_total[index]).text(sub_total);
				total =total+parseFloat(sub_total);
			});
			$("#saleTotal").text(total.toFixed(2));
		});


		$("#lpId").trigger("change");

	});
</script>
