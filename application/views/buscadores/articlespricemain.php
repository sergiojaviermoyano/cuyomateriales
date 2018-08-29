<!-- Modal -->

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
var id___Main, detail___Main, nextFocus___Main, price___Main;
var timer__Main, timeout__Main = 1000;
var row__Main = 0, rows__Main = 0;
var move__Main = 0;
var minLenght__Main = 0;
/*
function buscadorArticlesPrice(string, id, detail, nextFocus, price){
  id___ = id;
  detail___ = detail;
  nextFocus___ = nextFocus;
  price___ = price;
  $('#txtArtPrice').val(string);
  $('#tableArtPriceDetail > tbody').html('');
  //$('#buscadorArticlesPrice').modal('show');
  setTimeout(function () { $('#txtArtPrice').focus(); BuscarArticlePrice();}, 1000);
}
*/
function BuscarArticlePriceMain(){
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
                                  /*
                                  row___ += '<td width="1%"><i style="color: #00a65a; cursor: pointer;" class="fa fa-fw fa-check-square"';
                                  row___ += 'onClick="seleccionarArticlePrice(' + result.artId + ', \'' + result.artDescription + '\', ' + calcularPrecioInterno(result) + ')"></i></td>';
                                  */
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
/*
$('#buscadorArticlesPrice').on('hidden.bs.modal', function() {
  $('#lblProducto').prop('disabled', false);
  $('#lblProducto').focus().select();
})
*/

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
        /*
        seleccionarArticlePrice(
                          $('#tableArtPriceDetail tbody tr:nth-child('+row__+') td:nth-child(5)')[0].innerHTML,
                          $('#tableArtPriceDetail tbody tr:nth-child('+row__+') td:nth-child(3)')[0].innerHTML,
                          ($('#tableArtPriceDetail tbody tr:nth-child('+row__+') td:nth-child(4)')[0].innerHTML).replace('$', '').trim()
                        );
        */
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

/*function seleccionarArticlePrice(id, desc, price){
    id___.val(id);
    detail___.val(desc);
    price___.html('$'+parseFloat(price).toFixed(2));
    $('#buscadorArticlesPrice').modal('hide');
    $('#lblProducto').prop('disabled', false);
    setTimeout(function () { nextFocus___.focus(); nextFocus___.select()}, 800);
}*/

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
