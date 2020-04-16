
<div class="row">
	<div class="col-xs-12">
		<div class="alert alert-danger alert-dismissable" id="errorCheque" style="display: none">
	        <h4><i class="icon fa fa-ban"></i> Error!</h4>
            <p id="msjError"></p>
      </div>
	</div>
</div>
    <div class="row">
        <div class="col-xs-2" style="margin-top: 10px;">Cliente: </div>
        <div class="col-xs-10">
            <select class="form-control select2" id="cliId" style="width: 100%;" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?> >
            <?php 
                echo '<option value="-1" selected></option>';
                foreach ($clientes as $c) {
                echo '<option value="'.$c['cliId'].'" '.($data['check']['cliId'] == $c['cliId'] ? "selected" : '').'>'.$c['cliApellido'].', '.$c['cliNombre'].' ('.$c['cliDocumento'].')</option>'; 
                }
            ?>
            </select>
        </div>
    </div><br>
    <div class="row">
        <div class="col-xs-2" style="margin-top: 10px;">Cheque: </div>
        <div class="col-xs-8"><input type="text" class="form-control" id="chequeNro" value="<?php echo $data['check']['cheNumero'];?>" placeholder="Número de cheque" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>></div>
        <div class="col-xs-2"><input type="text" class="form-control" id="chequeImporte" value="<?php echo $data['check']['cheImporte'];?>" placeholder="0.00" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>></div>
    </div><br>
    <div class="row">
        <div class="col-xs-2" style="margin-top: 10px;">Vencimiento: </div>
        <div class="col-xs-2"><input type="text" class="form-control" id="chequeVto" value="<?php echo ($data['check']['cheVencimiento'] == '' ? '' : date('d-m-Y', strtotime($data['check']['cheVencimiento'])));?>" placeholder="dd-mm-aaaa" readonly="readonly"></div>
        <div class="col-xs-2" style="margin-top: 10px;">Banco: </div>
        <div class="col-xs-6">
        <select class="form-control select2" id="bancoId" style="width: 100%;" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>>
            <?php 
            echo '<option value="-1" selected></option>';
            foreach ($bancos as $b) {
                echo '<option value="'.$b['bancoId'].'" '.($data['check']['bancoId'] == $b['bancoId'] ? "selected" : '').'>'.$b['bancoDescripcion'].'</option>'; //data-balance="'.$c['balance'].'" data-address="'.$c['cliAddress'].'" data-dni="'.$c['cliDni'].'"
            }
            ?>
        </select>
        </div>
    </div><br>
    <div class="row">
        <div class="col-xs-2">Observación: </div>
        <div class="col-xs-10"><input type="text" class="form-control" id="cheObservacion" maxlength="100" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?> value="<?php echo $data['check']['cheObservacion'];?>"></div>
    </div>
    <?php
    if($data['act'] != 'Add'){
        ?>
        <hr>
        <div class="row">
            <div class="col-xs-6 text-center">
                <input type="radio" id="DE" name="cheUso" value="DE" <?php echo ($data['check']['cheEstado'] == 'DE' ? 'checked' : '')?> <?php echo ($data['act'] == 'View' ? 'disabled="disabled"' : '');?>>
                <label for="DE">Depositado</label>
            </div>
            <div class="col-xs-6 text-center">
                <input type="radio" id="UT" name="cheUso" value="UT" <?php echo ($data['check']['cheEstado'] == 'UT' ? 'checked' : '')?> <?php echo ($data['act'] == 'View' ? 'disabled="disabled"' : '');?>>
                <label for="UT">Utilizado</label>
            </div>
        </div><br>
        <div class="row">
            <div class="col-xs-2">Detalle: </div>
            <div class="col-xs-10"><input type="text" class="form-control" id="cheDetalle" maxlength="100" value="<?php echo $data['check']['cheDetalle'];?>" <?php echo ($data['act'] == 'View' ? 'disabled="disabled"' : '');?>></div>
        </div>
        <?php
    }
    ?>