
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
if($data['act'] == 'Add' ){ ?>
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

<!--
<div class="form-group">
	<label class="col-sm-3">Artículo <strong style="color: #dd4b39">*</strong>:   </label>
  	<div class="col-sm-5 " id="articleField_container">
    	<input type="text" class="form-control typeahead" placeholder="Articulo" id="articleField" name="articleField"   data-provide="typeahead" data-id="" >
		<span id="articleField_error" class="help-block hidden">Seleccione un Artículo.</span>
	</div>
  	<div class="col-sm-2" id="articleCant_container">
    	<input type="text" class="form-control" placeholder="Cantidad" id="articleCant" name="articleCant"  >
		<span id="articleCant_error" class="help-block hidden">Ingrese una Cantidad mayor a 0.</span>
	</div>
  	<div class="col-sm-2">
    	<button id="addItem_bt" type="button" class="btn btn-success btn-sm " name="button"> <i class="fa fa-plus" aria-hidden="true"></i> Agregar</button>
  	</div>
</div>
-->
<div class="row">
  <div class="col-xs-4">
      <label style="margin-top: 7px;">Producto: </label>
    </div>
  <div class="col-xs-5">
      <input type="number" class="form-control" id="artId" value="" min="0" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?> >
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
				<?php foreach ($data['detalleCompra'] as $key => $value):?>
					<tr>
						<td width="1%"><i style="color: #00a65a; cursor: pointer;" class="fa fa-fw fa-check-square" onClick="delete_(1)"></i></td>
						<td width="10%"><?php echo $value['artBarCode']?></td>
						<td><?php echo $value['artDescripcion']?></td>
						<td width="10%" class="td_cant" style="text-align: right"   data-pventa="<?php echo $value['artPCosto']?>" ><?php echo  (int)$value['ocdCantidad']?></td>
						<td width="10%" class="td_pventa"  style="text-align: right" ><?php echo $value['artPCosto']?></td>
						<td width="10%" class="td_total" style="text-align: right"><?php echo $value['artPVenta']?></td>
						<td style="display: none"><?php echo $value['artId']?></td>
						<td style="display: none"><?php echo $value['artPVenta']?></td>
						<td style="display: none"><?php echo $value['artPCosto']?></td>
					</tr>
				<?php endforeach;?>
      </tbody>
    </table>

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

<script>
/*
  $(function(){
		$('.typeahead').typeahead({
			hint: true,
		  highlight: true,
		  minLength: 1,
			source: function (query, process) {
				var strng=$('.typeahead').val();
				var input = [];
				input.push(strng);
				var data_ajax={
						method: "POST",
						url: "index.php/article/searchByAll",
					  data: { code: strng },
					  success:function(data){
							objects = [];
							map = {};
							$.each(data, function(i, object) {
								var key= object.artBarCode+" - "+object.artDescription
								map[key] = object;
								objects.push(key);
							});
							return process(objects);
					  },
					  error:function(error_msg){
					  	alert( "error_msg: " + error_msg );
					  },
						dataType: 'json'
					};
					$.ajax(data_ajax);
        },updater: function(item) {
					var data=map[item];
					console.debug("===> data: %o",data);
					$('#articleField').attr('data-artBarCode',data.artBarCode);
					$('#articleField').attr('data-artDescription',data.artDescription);
					$('#articleField').attr('data-pVenta',data.pVenta);
          return data.artDescription;
        },
  		autoSelect: false
		});




		$("#addItem_bt").on('click',function(){

			if($("#articleField").val().length<1){
				$("#articleField_container").addClass("has-error");
				$("#articleField_error").removeClass("hidden");
				return false;
			}else{
				$("#articleField_container").removeClass("has-error");
				$("#articleField_error").addClass("hidden");
			}
			if($("#articleCant").val().length<1){
				$("#articleCant_container").addClass("has-error");
				$("#articleCant_error").removeClass("hidden");
				return false;
			}else{
				$("#articleCant_container").removeClass("has-error");
				$("#articleCant_error").addClass("hidden");
			}

			var articleData=$("#articleField").data();
			console.debug("===> articleData: %o",articleData);
			var new_row="";
			new_row +="<tr>";
			new_row +="<td>"+articleData.artbarcode+"</td>";
			new_row +="<td>"+articleData.artdescription+"</td>";
			new_row +="<td>"+$("#articleCant").val()+"</td>";
			new_row +="<td> $ "+articleData.pventa+"</td>";
			new_row +="<td class='text-center'>"+($("#articleCant").val()*articleData.pventa)+"</td>";
			//new_row +=""
			new_row +="</tr>";
			$("#articleField").data(null);
			console.debug("===> new_row: %o",new_row);
			console.debug("===> new_row: %o",$("#articleField").data());

			$("#order_detail tbody").append(new_row);
			$("#articleField").val(null).focus();
			$("#articleCant").val(null);
		})
		/*.on('keypress',function(){
			console.debug("===> value: %o",$(this).val());
		});*/
/*
  });
*/

var isOpenWindow = false;
  $('#artId').keyup(function(e){
    var code = e.which;
    if(code==13)e.preventDefault();
    if(code==32||code==13||code==188||code==186){
        //Buscar articulo
        Buscar();
      }
  });
var idSale = 1;
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
														console.debug(" =>>> %o * ( %o ) / 100: ",pVenta,margin,(pVenta * (margin / 100)));
                 						pVenta += pVenta * (margin / 100);
                 					}
                          WaitingClose();
                          var cantidad = parseInt($('#artCant').val() == '' ? 1 : $('#artCant').val());
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
    $('#modalReception').modal('hide');
    cerro();
    $('#modalSearch').modal({ backdrop: 'static', keyboard: false });
    $('#modalSearch').modal('show');
    setTimeout(function () { $('#artIdSearch').focus(); }, 1000);
  }

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


	$(function(){
		$('#lpId').on('change',function(){
			var selected = $('#lpId').find('option:selected');
			var margin = parseFloat(selected.data('porcent'));
			var td_cant=$("table").find("td.td_cant");
			var td_pventa=$("td_pventa").find("td.td_pventa");
			var td_total=$("table").find("td.td_total");
			var total=0;
			$.each(td_cant,function(index,item){
				var cantidad=parseInt($(item).text());
				var pVenta = $(item).data('pventa');
				pVenta=parseFloat(pVenta);
				if(margin > 0){
					pVenta += pVenta * (margin / 100);
				}
				var sub_total=(parseFloat(pVenta) * parseFloat(cantidad)).toFixed(2);
				$(td_total[index]).text(sub_total);
				total =total+parseFloat(sub_total);
			});
			$("#saleTotal").text(total);
		});


		$("#lpId").trigger("change");

	});
</script>
