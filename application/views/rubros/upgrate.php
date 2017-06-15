<input type="hidden" id="permission" value="<?php echo $permission;?>">
<section class="content">
  <div class="row">
    <div class="col-xs-6">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Actualizar Precios Por Rubros</h3>

        </div><!-- /.box-header -->
        <div class="box-body">
          <form class="form-horizontal" action="" method="post">
            <div class="form-group">
              <label for="rubId" class="control-label col-sm-4">Rubro</label>
              <div class="col-sm-6">
                <select class="form-control" name="rubId" id="rubId">
                  <option value=""></option>
                  <?php foreach($rubros as $key => $rubro ):?>
                    <option value="<?php echo $rubro['rubId']?>"><?php echo $rubro['rubDescripcion']?></option>
                  <?php endforeach;?>
                </select>
              </div>
            </div>


          <div class="form-group">
            <label for="subrId" class="control-label col-sm-4">Sub Rubro</label>
            <div class="col-sm-6">
              <select class="form-control" name="subrId" id="subrId">
                <option value=""></option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label for="subrId" class="control-label col-sm-4">Es Procentaje</label>
            <div class="col-sm-6">
              <label class="radio-inline">
                <input type="checkbox" name="artMarginIsPorcent" id="artMarginIsPorcent" value="1" >
              </label>
            </div>
          </div>

          <div class="form-group">
            <label for="subrId" class="control-label col-sm-4">Importe a Actualizar</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="incrementValue" id="incrementValue" value="">
            </div>
          </div>

          <button type="submit" name="bt_update" id="bt_update" class="btn btn-success pull-right"> Actualizar </button>
        </form>

        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div><!-- /.col -->
  </div><!-- /.row -->
</section><!-- /.content -->

<script type="text/javascript">
  $(function(){

    $("#incrementValue").maskMoney({allowNegative: true, thousands:'', decimal:'.'});


    $("#rubId").on('change',function(){
      var rubId=$(this).val();
      WaitingOpen('Cargando Subrubro');
        $.ajax({
              method: 'GET',
              data: { rubId : rubId},
              url: 'index.php/rubro/getSubRubro_by_rubro',
              success: function(result){
                 WaitingClose();
                 $("#subrId").empty();
                 var output ='<option value=""> Seleccione SubRubro</option>';
                 $.each(result,function(index,item){
                   output +='<option value="'+item.subrId+'"> '+item.subrDescripcion+'</option>';
                 });
                 $("#subrId").html(output);
               },
               error: function(result){
                 WaitingClose();
                 //ProcesarError(result.responseText, 'modalRubro');
              },
              dataType: 'json'
          });
    });


    $("form").submit(function(){
      var data=$(this).serialize();
      WaitingOpen('Cargando Subrubro');
      console.debug("===> data: %o",data);

      $.ajax({
            method: 'POST',
            data:data,
            url: 'index.php/article/update_prices_by_rubro',
            success: function(result){
              console.debug(result);
               WaitingClose();
               //$('#modalArticle').modal('hide');
               setTimeout("cargarView('rubro', 'upgrate', '"+$('#permission').val()+"');",1000);
               /*
               $("#subrId").empty();
               var output ='<option value=""> Seleccione SubRubro</option>';
               $.each(result,function(index,item){
                 output +='<option value="'+item.subrId+'"> '+item.subrDescripcion+'</option>';
               });
               $("#subrId").html(output);*/
             },
             error: function(result){
               WaitingClose();
               //ProcesarError(result.responseText, 'modalRubro');
            },
            dataType: 'json'
        });
      return false;
    });

  });
</script>
