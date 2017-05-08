<div class="row">
	<div class="col-xs-12">
		<div class="alert alert-danger alert-dismissable" id="errorGrp" style="display: none">
	        <h4><i class="icon fa fa-ban"></i> Error!</h4>
	        Revise que todos los campos esten completos
      </div>
	</div>
</div>
<div class="row">
	<div class="col-xs-3">
      <label style="margin-top: 7px;">Nombre <strong style="color: #dd4b39">*</strong>: </label>
    </div>
	<div class="col-xs-5">
      <input type="text" class="form-control" placeholder="Nombre" id="grpName" value="<?php echo $data['name'];?>" <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?>  >
    </div>
</div>
<hr>
<?php
	foreach ($data['list'] as $it) {
		?>
		<div id="permission">
		<a role="button" data-toggle="collapse" href="#collapse<?php echo $it->menuName;?>" aria-expanded="false" aria-controls="collapse<?php echo $it->menuName;?>" class="modal-title"><?php echo str_replace("_", " ", $it->menuName);?></a>
		<div class="collapse" id="collapse<?php echo $it->menuName;?>">
		  <div>
		    <?php
		    	if(count($it->childrens) > 0)
		    	{
		    		foreach ($it->childrens as $c)
		    		{
		    			?>
		    			<a role="button" data-toggle="collapse" href="#collapse<?php echo $c->menuName;?>" aria-expanded="false" aria-controls="collapse<?php echo $c->menuName;?>" class="modal-title"><i class="fa fa-fw fa-arrow-right" style="color: #00a65a"></i><?php echo str_replace("_", " ", $c->menuName);?></a><br>
		    			<div class="collapse" id="collapse<?php echo $c->menuName;?>">
		  					<div>
		  					<?php
			  					foreach ($c->actions as $a) {
			    						if($a['grpactId'] == null)
						    				echo '<input type="checkbox" id="'.$a['menuAccId'].'" style="margin-left: 10%;" '.($data['read'] == true ? 'disabled="disabled"' : '').'>'.$a['actDescription'].'<br>';
						    			else
						    				echo '<input type="checkbox" id="'.$a['menuAccId'].'" style="margin-left: 10%;" '.($data['read'] == true ? 'disabled="disabled"' : '').' checked>'.$a['actDescription'].'<br>';
							    				}
		    				?>
		  					</div>
		  				</div>
		    			<?php
		    		}
		    	}
		    	else
		    	{
		    		foreach ($it->actions as $a) {
		    			if($a['grpactId'] == null)
		    				echo '<input type="checkbox" id="'.$a['menuAccId'].'" style="margin-left: 5%;" '.($data['read'] == true ? 'disabled="disabled"' : '').'>'.$a['actDescription'].'<br>';
		    			else
		    				echo '<input type="checkbox" id="'.$a['menuAccId'].'" style="margin-left: 5%;" '.($data['read'] == true ? 'disabled="disabled"' : '').' checked>'.$a['actDescription'].'<br>';
		    		}
		    	}
		    ?>
		  </div>
		</div>	
		</div>
		<?php
	}
?>