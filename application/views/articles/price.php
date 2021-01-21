<input type="hidden" id="permission" value="<?php echo $permission;?>">
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title"><i class="fa fa-fw fa-search" style="color: #3c8dbc"></i> Consulta de Precios</h3>
          <?php
          if (strpos($permission,'Add') !== false) {
            echo '<button class="btn btn-block btn-success" style="width: 100px; margin-top: 10px;" data-toggle="modal" onclick="LoadArt(0,\'Add\')" id="btnAdd">Agregar</button>';
          }
          ?>
        </div><!-- /.box-header -->
        <div class="box-body">
            <?php 
            $data['read'] = count($list) > 0 ? true : false;
            
            ?> 

            <div class="row">
                <div class="col-xs-3">
                    <label style="margin-top: 7px;">Lista de Precio: </label>
                </div>
                <div class="col-xs-3">
                <select class="form-control" id="lpId"  <?php echo ($data['read'] == true ? 'disabled="disabled"' : '');?> >
                    <?php foreach ($list as $key => $item):?>
                      <option value="<?php echo $item['lpId'];?>" data-porcent="<?php echo $item['lpMargen'];?>"><?php echo $item['lpDescripcion'];?> </option>
                    <?php endforeach;?>
                    </select>
                </div>

                <div class="col-xs-3">
                    <button class="btn  btn-default" style=""  onclick="Exportar()" id="btnExportar">
                        <i class="fa fa-fw fa-file-pdf-o" style="color: #dd4b39; font-size: 30px;"></i>
                        Exportar Artículos
                    </button>
                </div>

                <div class="col-xs-3">
                    <button type="button" class="btn btn-info" id="btnDwl"><a href="./assets/reports/listado.pdf" download="">Descargar</a></button>
                </div>
            </div><br>

            <div class="row">
                <div class="col-xs-12">
                    <div class="modal-dialog" role="document" style="width: 100%" ><!--style="width: 50%"-->
                    <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><i class="fa fa-search" style="color: #3c8dbc"></i> Buscar Artículo</h4>
                    </div>
                    <div class="modal-body" id="buscadorArticlesPriceBodyMain">

                        <div class="row" id="divBuscadorMain">
                        <div class="col-xs-10 col-xs-offset-1"><input type="text" class="form-control" id="txtArtPriceMain" value=""></div>
                        <div class="col-xs-1"><img style="display: none" id="loadingArtPriceMain" src="<?php  echo base_url();?>assets/images/loading.gif" width="35px"></div>
                            <!--
                            <input type="text" id="type" />
                            <span id="status"></span>
                            -->
                        </div><br>

                        <div class="row" style="max-height:auto; overflow-x: auto;" id="tableArtPrMain">
                        <div class="col-xs-10 col-xs-offset-1">
                            <table id="tableArtPriceDetailMain" style="max-height:auto; display: table;" class="table table-bordered" width="100%">
                            <tbody>

                            </tbody>
                            </table>
                        </div>
                        </div>

                    </div>
                    </div>
                </div>


                <script>
                $('#btnDwl').hide();
                var id___Main, detail___Main, nextFocus___Main, price___Main;
                var timer__Main, timeout__Main = 1000;
                var row__Main = 0, rows__Main = 0;
                var move__Main = 0;
                var minLenght__Main = 0;

                function BuscarArticlePriceMain(){
                    var selected = $('#lpId').find('option:selected');
                     var margin = parseFloat(selected.data('porcent'));
                     
                if($('#txtArtPriceMain').val().length > minLenght__Main){
                    //Buscar
                    $("#loadingArtPriceMain").show();
                    $('#tableArtPriceDetailMain > tbody').html('');
                    row__Main = 0;
                    rows__Main = 0;
                    $.ajax({
                        type: 'POST',
                        data: { code: $('#txtArtPriceMain').val() },
                        url: 'index.php/article/searchByAll',
                        success: function(resultList){
                                        $("#loadingArtPriceMain").hide();
                                        if(resultList != false){
                                            if(resultList.length == 0){
                                            $('#divBuscadorMain').addClass('has-error');
                                            setTimeout(function () { $('#divBuscadorMain').removeClass('has-error');}, 1000);
                                            } else {
                                            $.each(resultList, function(index, result){
                                                var row___Main = '<tr>';
                                                if(margin != 0)
                                                    result.pVenta += result.pVenta * (margin / 100);
                                                row___Main += '<td style="text-align: right">'+result.artBarCode+'</td>';
                                                row___Main += '<td>'+result.artDescription+'</td>';
                                                row___Main += '<td style="text-align: right"> $ ' + result.pVenta.toFixed(2) + '</td>';
                                                row___Main += '<td style="display: none">'+result.artId+'</td>';
                                                row___Main += '<td style="text-align: right">'+(result.stock == null ? '0.00' : result.stock)+'</td>';
                                                row___Main += '</tr>';
                                                $('#tableArtPriceDetailMain > tbody').prepend(row___Main);
                                                rows__Main++;
                                            });

                                            setTimeout(function () { $('#txtArtPriceMain').focus();}, 1000);
                                            }
                                        } else {
                                            $('#divBuscadorMain').addClass('has-error');
                                            setTimeout(function () { $('#divBuscadorMain').removeClass('has-error');}, 1000);
                                        }
                                },
                        error: function(result){
                                $("#loadingArtPriceMain").hide();
                                ProcesarError(result.responseText, 'buscadorArticlesPriceMain');
                            },
                            dataType: 'json'
                    });
                }else{
                    $("#loadingArtPriceMain").hide();
                }
                }

                $('#txtArtPriceMain').keyup(function(e){
                    var code = e.which;
                    if(code != 40 && code != 38 && code != 13){
                    if($('#txtArtPriceMain').val().length >= minLenght__Main){
                        // Clear timer if it's set.
                        if (typeof timer__Main != undefined)
                        clearTimeout(timer__Main);

                        // Set status to show we're typing.
                        //$("#status").html("Typing ...").css("color", "#009900");

                        timer__Main = setTimeout(function()
                        {
                        //$("#status").html("Stopped").css("color", "#990000");
                        $("#loadingArtPriceMain").show();
                        BuscarArticlePriceMain();
                        row__Main = 0;
                        }, timeout__Main);
                    }
                    } else {
                    var removeStyle = $("#tableArtPriceDetailMain > tbody tr:nth-child("+row__Main+")");
                    if(code == 13){//Seleccionado
                        removeStyle.css('background-color', 'white');

                    }

                    if(code == 40){//abajo
                        if((row__Main + 1) <= rows__Main){
                        row__Main++;
                        if(row__Main > 5){
                        }
                        removeStyle.css('background-color', 'white');
                        }
                        var rowE = $("#tableArtPriceDetailMain > tbody tr:nth-child("+row__Main+")");
                        rowE.css('background-color', '#D8D8D8');
                        //animate();
                    }
                    if(code == 38) {//arriba
                        if(row__Main >= 2){
                        row__Main--;
                        removeStyle.css('background-color', 'white');
                        }
                        var rowE = $("#tableArtPriceDetailMain > tbody tr:nth-child("+row__Main+")");
                        rowE.css('background-color', '#D8D8D8');
                        //animate();
                    }
                    }
                });

                function calcularPrecioInternoMain(article){
                var precioCosto 				= article['artCoste'];
                    var cotizacionDolar 		= article['dolar'];
                var margenMi      			= article['artMarginMinorista'];
                var margenMiEsPor 			= article['artMarginMinoristaIsPorcent'];

                var pventaMinorista = 0;

                    //Precio en Dolar
                if(article['artCosteIsDolar'] == "0")
                    cotizacionDolar = 1;
                    var precioCosto = precioCosto * cotizacionDolar;

                //Minorista
                if(margenMiEsPor == true || margenMiEsPor == "1"){
                    var importe = (parseFloat(margenMi) / 100) * parseFloat(precioCosto);
                    pventaMinorista = parseFloat(parseFloat(importe) + parseFloat(precioCosto));
                } else {
                    pventaMinorista = parseFloat(parseFloat(precioCosto) + parseFloat(margenMi));
                }

                    return pventaMinorista;
                }

                </script>
                </div>
            </div>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div><!-- /.col -->
  </div><!-- /.row -->
</section><!-- /.content -->

<script>
function Exportar(){
    var selected = $('#lpId').find('option:selected');
    var margin = parseFloat(selected.data('porcent'));
    WaitingOpen('Generando reporte');
    $.ajax({
            type: 'POST',
            data: { marg: margin },
            url: 'index.php/article/exportar',
            success: function(result){
                        WaitingClose();
                        $('#btnDwl').show();
                    },
            error: function(result){
                    WaitingClose();
                    $('#btnDwl').hide();
                    ProcesarError(result.responseText, 'buscadorArticlesPriceMain');
                },
                dataType: 'json'
        });
}
</script>