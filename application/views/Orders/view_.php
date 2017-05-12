
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
          <option value="">Lista de Precios</option>
          <?php foreach ($Clientes as $key => $item):?>
            <option value="<?php echo $item['cliId'];?>" <?php echo ($data['order']['cliId']==$item['cliId'])?'selected':''?> ><?php echo $item['cliNombre'];?> </option>
          <?php endforeach;?>

        </select>
      </div>
    </div>

    <hr>

    <div class="form-group">
      <label class="col-sm-3">Artículo <strong style="color: #dd4b39">*</strong>:   </label>
      <div class="col-sm-5">
        <input type="text" class="form-control" placeholder="Articulo" id="articleField" name="articleField" >
      </div>
      <div class="col-sm-2">
        <input type="text" class="form-control" placeholder="Cantidad" id="articleCant" name="articleCant"  >
      </div>
      <div class="col-sm-2">
        <button type="button" class="btn btn-success btn-sm " name="button"> <i class="fa fa-plus" aria-hidden="true"></i> Agregar</button>
      </div>
    </div>

    <table id="order_detail" class="table table-bordered table-hover">
      <thead>
        <tr>
          <th >Código</th>
          <th>Descripcion</th>
          <th>Cantidad</th>
          <th>P.Venta</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
      </tbody>

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




<script>
  $(function(){
    $("#order_detail").DataTable();
  });

</script>
