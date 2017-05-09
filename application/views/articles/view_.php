
<div class="row">
	<div class="col-xs-12">
		<div class="alert alert-danger alert-dismissable" id="error" style="display: none">
	        <h4><i class="icon fa fa-ban"></i> Error!</h4>
	        Revise que todos los campos esten completos
      </div>
	</div>
</div>
    
      <!-- Código de Articulo -->
      <div class="row">
        <div class="col-xs-4">
            <label style="margin-top: 7px;">Código <strong style="color: #dd4b39">*</strong>: </label>
          </div>
        <div class="col-xs-5">
            <input type="text" class="form-control" id="artBarCode" value="<?php echo $data['article']['artBarCode'];?>" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?> >
          </div>
      </div><br>

      <!-- Código del Artículo -->
      <div class="row">
        <div class="col-xs-4">
            <label style="margin-top: 7px;">Descripción <strong style="color: #dd4b39">*</strong>: </label>
          </div>
        <div class="col-xs-5">
            <input type="text" class="form-control" id="artDescription" value="<?php echo $data['article']['artDescription'];?>" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?> >
          </div>
      </div><br>
      
      <!-- Descripción del Artículo -->
      <div class="row">
        <div class="col-xs-4">
            <label style="margin-top: 7px;">Se Compra x Caja <strong style="color: #dd4b39">*</strong>: </label>
          </div>
        <div class="col-xs-1">
            <input type="checkbox" id="artIsByBox" style="margin-top:10px;" <?php echo($data['article']['artIsByBox'] == true ? 'checked': ''); ?> <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?> >
          </div>
        <div class="col-xs-3">
            <label style="margin-top: 7px;">Unidades <strong style="color: #dd4b39">*</strong>: </label>
          </div>
        <div class="col-xs-4">
            <input type="text" class="form-control" id="artCantBox" value="<?php echo $data['article']['artCantBox'];?>" <?php echo (($data['article']['artIsByBox'] != true || ($data['action'] == 'View' || $data['action'] == 'Del'))? 'disabled="disabled"' : '');?>  >
          </div>
      </div><br>

      <!-- Tipo de Material -->
      <div class="row">
        <div class="col-xs-4">
            <label style="margin-top: 7px;">Precio Costo <strong style="color: #dd4b39">*</strong>: </label>
          </div>
        <div class="col-xs-5">
            <input type="text" class="form-control" id="artCoste" value="<?php echo $data['article']['artCoste'];?>" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>  >
          </div>
      </div><br>

      <!-- Tipo de Madera -->
      <div class="row">
        <div class="col-xs-4">
            <label style="margin-top: 7px;">Margen : </label>
          </div>
        <div class="col-xs-4">
           <input type="text" class="form-control" id="artMargin" value="<?php echo $data['article']['artMargin'];?>" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>  >
          </div>
        <div class="col-xs-3">
            <label style="margin-top: 7px;">Es Porcentaje <strong style="color: #dd4b39">*</strong>: </label>
          </div>
        <div class="col-xs-1">
            <input type="checkbox" id="artMarginIsPorcent" style="margin-top:10px;" <?php echo($data['article']['artMarginIsPorcent'] == true ? 'checked': ''); ?> <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?> >
          </div>
      </div><br>

      <!-- -->

      <!-- Se vende por pie -->
      <div class="row">
        <div class="col-xs-4">
            <label style="margin-top: 7px;">Precio Venta : </label>
          </div>
        <div class="col-xs-5" style="padding-top: 7px;">
            <strong id="pventa">0.00</strong>
          </div>
      </div><br>

      <!-- -->
      <div class="row">
        <div class="col-xs-4">
            <label style="margin-top: 7px;">Estado: </label>
          </div>
        <div class="col-xs-5">
            <select class="form-control" id="artEstado"  <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?> >
              <?php 
                  echo '<option value="AC" '.($data['article']['artEstado'] == 'AC' ? 'selected' : '').'>Activo</option>';
                  echo '<option value="IN" '.($data['article']['artEstado'] == 'IN' ? 'selected' : '').'>Inactivo</option>';
                  echo '<option value="SU" '.($data['article']['artEstado'] == 'SU' ? 'selected' : '').'>Suspendido</option>';
              ?>
            </select>
          </div>
      </div>


      <div class="form-group">
        <label class="col-sm-4"> Fraccionado </label>
        <div class="col-sm-5">
          <input type="checkbox" class="checkbox" value="1" name="artSeFracciona" id='artSeFracciona' <?php (isset($data['artSeFracciona']) &&  $data['artSeFracciona']==1) ?'checked':''?> >
        </div>
      </div>
      <br>
      <div class="form-group">
         <label class="col-sm-4"> Sub-rubro </label>
        <div class="col-sm-5">
          <select class="form-control" name="subrId" id="subrId">  
            <option>Seleccionar Rubro</option>
            <?php foreach ($rubros as $key => $item):?>            
              <option value="<?php echo $item['rubId'];?>" <?php echo ($data['article']['subrId']==$item['rubId'])?'selected':''?> ><?php echo $item['rubDescripcion'];?></option>
            <?php endforeach;?>
            
          </select>
        </div>
      </div>
      
      <br>
      <div class="form-group">
        <label class="col-sm-4"> Condición de IVA  </label>
        <div class="col-sm-5">
          <select class="form-control" name="ivaId" id="ivaId">  
            <option>Seleccionar IVA</option>
            <?php foreach ($ivaAliCuotas as $key => $item):?>
            <option value="<?php echo $item['ivaId'];?>" <?php echo ($data['article']['ivaId']==$item['ivaId'])?'selected':''?> ><?php echo $item['ivaDescripcion'];?></option>
            <?php endforeach;?>

          </select>
        </div>
      </div>
      <br>
      <div class="form-group">
        <label class="col-sm-4"> Mínimo</label>
        <div class="col-sm-5">
          <input type="" class="form-control" id="artMinimo" name="artMinimo" value="<?php echo $data['article']['artMinimo'];?>">
        </div>
      </div>

      <br>
      <div class="form-group">
        <label class="col-sm-4"> Medio</label>        
        <div class="col-sm-5">
          <input type="" class="form-control" id="artMedio" name="artMedio" value="<?php echo $data['article']['artMedio'];?>">
        </div>       
      </div>

      <br>
      <div class="form-group">
        <label class="col-sm-4"> Maximo</label>  
        <div class="col-sm-5">
          <input type="" class="form-control" id="artMaximo" name="artMaximo" value="<?php echo $data['article']['artMaximo'];?>">
        </div>
      </div>




<script>

$('#artIsByBox').click(function() {
  if($('#artIsByBox').is(':checked')){
    $('#artCantBox').prop('disabled', false);
  } else {
    $('#artCantBox').val('');
    $('#artCantBox').prop('disabled', true);
  }
  CalcularPrecio();
});

$('#artMargin').keyup(function(){
  CalcularPrecio();
});

$('#artMarginIsPorcent').click(function() {
  CalcularPrecio();
});

$('#artCoste').keyup(function(){
  CalcularPrecio();
});

$('#artCantBox').keyup(function(){
  CalcularPrecio();
});


function CalcularPrecio(){
  var precioCosto = $('#artCoste').val() == '' ? 0 : parseFloat($('#artCoste').val()).toFixed(2);
  var margen      = $('#artMargin').val() == '' ? 0 : parseFloat($('#artMargin').val()).toFixed(2);
  var margenEsPor = $('#artMarginIsPorcent').is(':checked');
  var cantCaja    = $('#artCantBox').val() == '' ? 0 : parseFloat($('#artCantBox').val()).toFixed(2);
  var esPorCaja   = $('#artIsByBox').is(':checked');


  var costoUnitario = parseFloat(precioCosto);
  if(esPorCaja == true){
    costoUnitario = parseFloat(parseFloat(precioCosto) / parseFloat(cantCaja)).toFixed(2);
  }

  var pVenta = 0;
  if(margenEsPor){
    var importe = (parseFloat(margen) / 100) * parseFloat(costoUnitario);
    pVenta = parseFloat(parseFloat(importe) + parseFloat(costoUnitario)).toFixed(2);
  } else {
    pVenta = parseFloat(parseFloat(costoUnitario) + parseFloat(margen)).toFixed(2);
  }

  $('#pventa').html(pVenta);
}

</script>