
<div class="row">
	<div class="col-xs-12">
		<div class="alert alert-danger alert-dismissable" id="errorOrder" style="display: none">
	        <h4><i class="icon fa fa-ban"></i> Error!</h4>
          <p>Revise que todos los campos esten completos<br></p>

      </div>
	</div>
</div>
  <div class="form-horizontal">
    <div class="form-group">
      <label class="col-sm-3">Lista de Precios <strong style="color: #dd4b39">*</strong>:  </label>
      <div class="col-sm-9">
        <select class="form-control" name="lpId" id="lpId" <?php echo ($data['lpId'] == true ? 'disabled="disabled"' : '');?>>
          <option value="">Lista de Precios</option>
          <?php foreach ($ListaPrecios as $key => $item):?>
            <option value="<?php echo $item['lpId'];?>" <?php echo ($data['order']['lpId']==$item['lpId'])?'selected':''?> ><?php echo $item['lpDescripcion'];?> </option>
          <?php endforeach;?>

        </select>
      </div>
    </div>

    <div class="form-group">

      <label class="col-sm-3">Cliente <strong style="color: #dd4b39">*</strong>:  </label>
      <div class="col-sm-9">
        <select class="form-control" name="lpId" id="lpId" <?php echo ($data['lpId'] == true ? 'disabled="disabled"' : '');?>>
          <option value="">Seleccionar Cliente</option>
          <?php foreach ($Clientes as $key => $item):?>
            <option value="<?php echo $item['cliId'];?>" <?php echo ($data['order']['cliId']==$item['cliId'])?'selected':''?> ><?php echo $item['cliNombre'];?> </option>
          <?php endforeach;?>

        </select>
      </div>
    </div>

		<div class="form-group">
      <label class="col-sm-3">Observación :  </label>
      <div class="col-sm-9">
        <textarea name="name" class="form-control" rows="2" ></textarea>
      </div>
    </div>

    <hr>

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

    <table id="order_detail" class="table table-bordered table-hover">
      <thead>
        <tr>
          <th >Código</th>
          <th>Descripcion</th>
          <th>Cantidad</th>
          <th>P.Venta</th>
          <th class="text-center">Total</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
			<tfoot>
				<tr>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th class="text-center">
						<span class="order-total"> $ XXXXXX</span>
					</th>
				</tr>
			</tfoot>
    </table>


    <!--
    <div class="form-group">
      <label class="col-sm-4">Descripción <strong style="color: #dd4b39">*</strong>:   </label>
      <div class="col-sm-8">
        <input type="text" class="form-control" placeholder="dd-mm-aaaa" id="ocFecha" name="ocFecha" value="<?php echo $data['order']['ocFecha'];?>" readonly="readonly" >
      </div>
    </div>
  -->


  <!--

      <div class="form-group">
      <label class="col-sm-4">Estado:   </label>
      <div class="col-sm-8">
        <select class="form-control" id="ocEstado"  <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?> >
              <?php
                  echo '<option value="AC" '.($data['order']['ocEstado'] == 'AC' ? 'selected' : '').'>Activo</option>';
                  echo '<option value="IN" '.($data['order']['ocEstado'] == 'IN' ? 'selected' : '').'>Inactivo</option>';
                  echo '<option value="SU" '.($data['order']['ocEstado'] == 'SU' ? 'selected' : '').'>Suspendido</option>';
              ?>
            </select>
      </div>
    </div>
  -->
    <!--
    <div class="form-group">
      <label class="col-sm-4">  </label>
      <div class="col-sm-8">
      </div>
    </div> -->


  </div>
  <script src="<?php echo base_url();?>assets/Typeahead/bootstrap3-typeahead.js"></script>
<script>
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
  });

</script>
