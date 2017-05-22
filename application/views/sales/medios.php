<?php
//var_dump($data);
?>
<div class="row">
	<div class="col-xs-6">
		<?php 
		foreach ($data['tmp'] as $key => $item):
			echo '<div class="row" onClick="showDiv('.$item['tmpId'].')" id="'.$item['tmpId'].'">';
				echo '<div class="col-xs-5">';
				echo $item['tmpDescripción'];
				echo '</div>';
				echo '<div class="col-xs-5" style="text-align: right"> $ <strong id="'.$item['tmpId'].'_total"> 0.00 </strong>';
				echo '</div>';
				echo '<div class="col-xs-1" style="text-align: center"><i class="fa fa-fw fa-chevron-right"></i></div>';
			echo '</div><hr>';
		endforeach;
		?>
		<div class="row">
			<div class="col-xs-5" style="text-align: right;">
				<label style="font-size: 15px; margin-top: 10px;">Total</label>
			</div>
			<div class="col-xs-6" style="text-align: right;">
				<label style="font-size: 20px; color: red;">$ <?php echo $data['total'];?></label>
			</div>
		</div> <br>
		<div class="row">
			<div class="col-xs-5" style="text-align: right;">
				<label style="font-size: 15px; margin-top: 10px;">Pagos</label>
			</div>
			<div class="col-xs-6" style="text-align: right;">
				<label style="font-size: 20px; color: green;">$ <strong id="pagos_suma"> 0.00 </strong></label>
			</div>
		</div>

	</div>
	<div class="col-xs-6">
		<?php 
		foreach ($data['tmp'] as $key => $item):
			echo '<div class="row" id="'.$item['tmpId'].'_load" style="display:none">';
			if(count($item['tmpD']) > 1){
				//Con listado
				echo '<div class="row">';
				echo '<div class="col-xs-3" style="margin-top: 7px;">';
				echo $item['tmpDescripción'];
				echo '</div>';
				echo '<div class="col-xs-1" style="text-align: center; margin-top: 7px;"><i class="fa fa-fw fa-credit-card"></i></div>';
				echo '<div class="col-xs-7">';
				echo '<select class="form-control" id="'.$item['tmpId'].'_medId">';
				echo '<option value="-1" selected>Tarjetas</option>';
				foreach ($item['tmpD'] as $key => $item_):  
	              echo '<option value="'.$item_['medId'].'">'.$item_['medDescripcion'].'</option>';
	            endforeach;
				echo '</select>';
				echo '</div>';
				echo '</div><br>';

				echo '<div class="row">';
				echo '<div class="col-xs-5" style="margin-top: 7px;">Importe</div>';
				echo '<div class="col-xs-1" style="text-align: center; margin-top: 7px;"><i class="fa fa-fw fa-dollar"></i></div>';
				echo '<div class="col-xs-5">';
				echo '<input class="form-control" id="'.$item['tmpId'].'_importe" value="" type="text">';
				?>
				<script>
				$('#'+<?php echo $item['tmpId'];?>+'_importe').maskMoney({allowNegative: true, thousands:'', decimal:'.'});
				</script>
				<?php
				echo '</div>';
				echo '</div><br>';

				
				echo '<div class="row">';
				echo '<div class="col-xs-4 col-xs-offset-1">'.$item['tmpDescripcion1'].'</div>';
				echo '<div class="col-xs-4"><input type="text" class="form-control" id="'.$item['tmpId'].'_des1" value=""></div>';
				echo '</div><br>';
				echo '<div class="row">';
				echo '<div class="col-xs-4 col-xs-offset-1">'.$item['tmpDescripcion2'].'</div>';
				echo '<div class="col-xs-4"><input type="text" class="form-control" id="'.$item['tmpId'].'_des2" value=""></div>';
				echo '</div><br>';
				echo '<div class="row">';
				echo '<div class="col-xs-4 col-xs-offset-1">'.$item['tmpDescripcion3'].'</div>';
				echo '<div class="col-xs-4"><input type="text" class="form-control" id="'.$item['tmpId'].'_des3" value=""></div>';
				echo '</div><br>';

				echo '<div class="row">';
				echo '<div class="col-xs-12" style="text-align: center;">';
				echo '<button type="button" class="btn btn-default" style="margin-right: 10px;" onclick="hideDiv('.$item['tmpId'].')">Cancelar</button>';
        		echo '<button type="button" class="btn btn-success" onclick="addItem(-1, '.$item['tmpId'].', \'C\')">Aceptar</button>';
        		echo '</div>';
				echo '</div>';
			} else {
				//Único valor
				echo '<div class="row">';
				echo '<div class="col-xs-5" style="margin-top: 7px;">';
				echo $item['tmpD'][0]['medDescripcion'];
				echo '</div>';
				echo '<div class="col-xs-1" style="text-align: center; margin-top: 7px;"><i class="fa fa-fw fa-dollar"></i></div>';
				echo '<div class="col-xs-5">';
				echo '<input class="form-control" id="'.$item['tmpD'][0]['medId'].'_importe" value="" type="text">';
				echo '</div>';
				?>
				<script>
				$('#'+<?php echo $item['tmpD'][0]['medId'];?>+'_importe').maskMoney({allowNegative: true, thousands:'', decimal:'.'});
				</script>
				<?php
				echo '</div><br>';
				echo '<div class="row">';
				echo '<div class="col-xs-12" style="text-align: center;">';
				echo '<button type="button" class="btn btn-default" style="margin-right: 10px;" onclick="hideDiv('.$item['tmpId'].')">Cancelar</button>';
        		echo '<button type="button" class="btn btn-success" onclick="addItem('.$item['tmpD'][0]['medId'].', '.$item['tmpId'].', \'S\')">Aceptar</button>';
        		echo '</div>';
				echo '</div>';
				/*?>
				<script>
					$("#<?php echo $item['tmpD'][0]['medId'];?>").maskMoney({allowNegative: false, thousands:'', decimal:'.'});
				</script>
				<?php*/
			}
			echo '</div>';
		endforeach;
		?>
	</div>
</div>

<script>
var LastDiv = "";
function showDiv(id){
	if(LastDiv != ""){
		$(LastDiv).hide();
	}
	LastDiv = '#'+id+'_load';
	$(LastDiv).show();
	var element = $('#'+id+'_medId').val();
	if(typeof element == "undefined" || element == null){
		$('#'+id+'_importe').focus();	
	} else {
		$('#'+id+'_medId').focus();
	}

}

function hideDiv(id){
	var div = '#'+id+'_load';
	$(div).hide();
}

</script>