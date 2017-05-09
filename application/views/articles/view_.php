
<div class="row">
	<div class="col-xs-12">
		<div class="alert alert-danger alert-dismissable" id="errorArt" style="display: none">
	        <h4><i class="icon fa fa-ban"></i> Error!</h4>
          <p>Revise que todos los campos esten completos<br></p>
	        
      </div>
	</div>
</div>
  <div class="form-horizontal">
    <div class="form-group">
      <label class="col-sm-4">Código <strong style="color: #dd4b39">*</strong>:  </label>
      <div class="col-sm-8">
        <input type="text" class="form-control" id="artBarCode" value="<?php echo $data['article']['artBarCode'];?>" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?> >
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-4">Descripción <strong style="color: #dd4b39">*</strong>:   </label>
      <div class="col-sm-8">
        <input type="text" class="form-control" id="artDescription" value="<?php echo $data['article']['artDescription'];?>" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?> >
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-4">Se Compra x Caja <strong style="color: #dd4b39">*</strong>:   </label>
      <div class="col-sm-2">
        <input type="checkbox" id="artIsByBox" style="margin-top:10px;" <?php echo($data['article']['artIsByBox'] == true ? 'checked': ''); ?> <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?> >
      </div>      
      <label class="col-sm-3">Unidades <strong style="color: #dd4b39">*</strong>: </label>
        <div class="col-sm-3">
          <input type="text" class="form-control" id="artCantBox" value="<?php echo $data['article']['artCantBox'];?>" <?php echo (($data['article']['artIsByBox'] != true || ($data['action'] == 'View' || $data['action'] == 'Del'))? 'disabled="disabled"' : '');?>  >
        </div>
    </div>
    <!--
    <div class="form-group">
        <label class="col-sm-4">Unidades <strong style="color: #dd4b39">*</strong>: </label>
        <div class="col-sm-8">
          <input type="text" class="form-control" id="artCantBox" value="<?php echo $data['article']['artCantBox'];?>" <?php echo (($data['article']['artIsByBox'] != true || ($data['action'] == 'View' || $data['action'] == 'Del'))? 'disabled="disabled"' : '');?>  >
        </div>
    </div>
    -->

    <div class="form-group">
      <label class="col-sm-4"> Precio Costo <strong style="color: #dd4b39">*</strong>:   </label>
      <div class="col-sm-8">
        <input type="text" class="form-control" id="artCoste" value="<?php echo $data['article']['artCoste'];?>" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>  >
      </div>
    </div>


    <div class="form-group">
      <label class="col-sm-4"> Margen :   </label>
      <div class="col-sm-2">
           <input type="text" class="form-control" id="artMargin" value="<?php echo $data['article']['artMargin'];?>" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>  >
      </div>
      <label class="col-sm-4"> Es Porcentaje <strong style="color: #dd4b39">*</strong>: </label>
      <div class="col-sm-2">
            <input type="checkbox" id="artMarginIsPorcent" style="margin-top:10px;" <?php echo($data['article']['artMarginIsPorcent'] == true ? 'checked': ''); ?> <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?> >

      </div>
    </div>

    <!--
    <div class="form-group">
      <label class="col-sm-4"> Es Porcentaje <strong style="color: #dd4b39">*</strong>: </label>
      <div class="col-sm-8">
            <input type="checkbox" id="artMarginIsPorcent" style="margin-top:10px;" <?php echo($data['article']['artMarginIsPorcent'] == true ? 'checked': ''); ?> <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?> >

      </div>
    </div> 
    -->


    <div class="form-group">
      <label class="col-sm-4"> Precio Venta :  </label>
      <div class="col-sm-8">
        <strong id="pventa">0.00</strong>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-4"> Fraccionado </label>
      <div class="col-sm-8">
        <input type="checkbox" class="checkbox" value="1" name="artSeFracciona" id='artSeFracciona'<?php echo($data['article']['artSeFracciona'] == true ? 'checked': ''); ?> <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>>
      </div>
    </div>

    <div class="form-group">
         <label class="col-sm-4"> Sub-rubro <strong style="color: #dd4b39">*</strong></label>
        <div class="col-sm-5">
          <select class="form-control" name="subrId" id="subrId" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>>  
            <option value="">Seleccionar Rubro</option>
            <?php foreach ($rubros as $key => $item):?>            
              <option value="<?php echo $item['subrId'];?>" <?php echo ($data['article']['subrId']==$item['subrId'])?'selected':''?> ><?php echo $item['rubDescripcion'];?> - <?php echo $item['subrDescripcion'];?></option>
            <?php endforeach;?>
            
          </select>
        </div>
      </div>
      
      <br>
      <div class="form-group">
        <label class="col-sm-4"> Condición de IVA  <strong style="color: #dd4b39">*</strong> </label>
        <div class="col-sm-5">
          <select class="form-control" name="ivaId" id="ivaId" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>>  
            <option value="">Seleccionar IVA</option>
            <?php foreach ($ivaAliCuotas as $key => $item):?>
            <option value="<?php echo $item['ivaId'];?>" <?php echo ($data['article']['ivaId']==$item['ivaId'])?'selected':''?> ><?php echo $item['ivaDescripcion'];?></option>
            <?php endforeach;?>

          </select>
        </div>
      </div>      
      <div class="form-group">
        <label class="col-sm-4"> Mínimo</label>
        <div class="col-sm-5">
          <input type="" class="form-control" id="artMinimo" name="artMinimo" value="<?php echo $data['article']['artMinimo'];?>" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-4"> Medio</label>        
        <div class="col-sm-5">
          <input type="" class="form-control" id="artMedio" name="artMedio" value="<?php echo $data['article']['artMedio'];?>" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>>
        </div>       
      </div>
      <div class="form-group">
        <label class="col-sm-4"> Maximo</label>  
        <div class="col-sm-5">
          <input type="" class="form-control" id="artMaximo" name="artMaximo" value="<?php echo $data['article']['artMaximo'];?>" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>>
        </div>
      </div>

      <div class="form-group">
      <label class="col-sm-4">Estado:   </label>
      <div class="col-sm-8">
        <select class="form-control" id="artEstado"  <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?> >
              <?php 
                  echo '<option value="AC" '.($data['article']['artEstado'] == 'AC' ? 'selected' : '').'>Activo</option>';
                  echo '<option value="IN" '.($data['article']['artEstado'] == 'IN' ? 'selected' : '').'>Inactivo</option>';
                  echo '<option value="SU" '.($data['article']['artEstado'] == 'SU' ? 'selected' : '').'>Suspendido</option>';
              ?>
            </select>
      </div>
    </div>

    <!--
    <div class="form-group">
      <label class="col-sm-4">  </label>
      <div class="col-sm-8">
      </div>
    </div> -->


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