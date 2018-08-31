<!-- Modal -->
<div class="modal fade" id="buscadorClientes" tabindex="3000" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="width: 50%"><!---->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><i class="fa fa-search" style="color: #3c8dbc"></i> Buscar Cliente</h4>
      </div>
      <div class="modal-body" id="buscadorClientesBody">

        <div class="row">
          <div class="col-xs-10 col-xs-offset-1"><input type="text" class="form-control" id="txtClientes" value=""></div>
          <div class="col-xs-1"><img style="display: none" id="loadingClientes" src="<?php  echo base_url();?>assets/images/loading.gif" width="35px"></div>
            <!--
            <input type="text" id="type" />
            <span id="status"></span>
            -->
        </div><br>

        <div class="row" style="max-height:350px; overflow-x: auto;" id="tableArt">
          <div class="col-xs-10 col-xs-offset-1">
            <table id="tableClientesDetail" style="max-height:340px; display: table;" class="table table-bordered" width="100%">
              <tbody>

              </tbody>
            </table>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>

<script>
var idClientes, lblNombre, lblDocumento, lblDomicilio, lblTelefono;
var timerClientes, timeoutClientes = 1000;
var rowClientes = 0, rowsClientes = 0;
var moveClientes = 0;
var minLenghtClientes = 0;
function buscadorClientes(nombre, documento, domicilio, telefono, id){
  idClientes = id;
  lblNombre = nombre;
  lblDocumento = documento;
  lblDomicilio = domicilio;
  lblTelefono = telefono;
  $('#txtClientes').val('');
  $('#tableClientesDetail > tbody').html('');
  setTimeout(function () { $('#txtClientes').focus(); BuscarClientes();}, 1000);
}

function BuscarClientes(){
  if($('#txtClientes').val().length > minLenghtClientes){
    //Buscar
    $("#loadingClientes").show();
    $('#tableClientesDetail > tbody').html('');
    rowClientes = 0;
    rowsClientes = 0;
    $.ajax({
          type: 'POST',
          data: { str: $('#txtClientes').val() },
          url: 'index.php/customer/buscadorClientes',
          success: function(resultList){
                        if(resultList != false){
                            $.each(resultList, function(index, result){
                                var row__ = '<tr>';
                                row__ += '<td width="1%"><i style="color: #00a65a; cursor: pointer;" class="fa fa-fw fa-check-square"';
                                row__ += 'onClick="seleccionarClientes('+result.cliId+', \''+result.cliNombre+'\', \''+result.cliApellido+'\', \''+result.cliDocumento+'\', \''+result.cliDomicilio+'\', \''+result.cliTelefono+'\')"></i></td>';
                                row__ += '<td width="15%">'+result.cliApellido+'</td>';
                                row__ += '<td>'+result.cliNombre+'</td>';
                                row__ += '<td>'+result.cliDocumento+'</td>';
                                row__ += '<td style="display: none">'+result.cliId+'</td>';
                                row__ += '</tr>';
                                $('#tableClientesDetail > tbody').prepend(row__);
                            });

                        }
                        if ($('#buscadorClientes').data('bs.modal') && $('#buscadorClientes').data('bs.modal').isShown){
                              $('#txtClientes').focus();
                            }else {
                              //Cerrado
                              $('#buscadorClientes').modal('show');
                              setTimeout(function () { $('#txtClientes').focus();}, 1000);
                            }
                      $("#loadingClientes").hide();
                },
          error: function(result){
                $("#loadingClientes").hide();
                ProcesarError(result.responseText, 'buscadorClientes');
              },
              dataType: 'json'
      });
  } else {
    $("#loadingClientes").hide();
    $('#buscadorClientes').modal('show');
    setTimeout(function () { $('#txtClientes').focus();}, 1000);
  }
}

  $('#txtClientes').keyup(function(e){
    var code = e.which;
    if(code != 40 && code != 38 && code != 13){
      if($('#txtClientes').val().length >= minLenghtClientes){
        // Clear timer if it's set.
        if (typeof timerClientes != undefined)
          clearTimeout(timerClientes);

        // Set status to show we're typing.
        //$("#status").html("Typing ...").css("color", "#009900");

        timerClientes = setTimeout(function()
        {
          //$("#status").html("Stopped").css("color", "#990000");
          $("#loadingClientes").show();
          BuscarClientes();
          rowClientes = 0;
        }, timeoutClientes);
      }
    }
  });

function seleccionarClientes(id, nombre, apellido, documento, domicilio, telefono){
    idClientes.val(id);
    lblNombre.html(apellido +' '+nombre);
    lblDocumento.html(documento);
    lblTelefono.html(telefono);
    lblDomicilio.html(domicilio);
    $('#buscadorClientes').modal('hide');
    setTimeout("$('#ocObservacion').focus();",800);
}

</script>
