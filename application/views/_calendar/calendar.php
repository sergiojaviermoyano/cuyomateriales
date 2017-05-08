        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-md-10">
              <div class="box box-primary">
                <div class="box-body no-padding">
                  <!-- THE CALENDAR -->
                  <div id="calendar"></div>
                </div><!-- /.box-body -->
              </div><!-- /. box -->
            </div><!-- /.col -->
            <div class="col-md-2">
              <button class="btn btn-block btn-success" style="width: 100px; margin-top: 10px;" data-toggle="modal" id="btnAdd">Programar</button>
              <button class="btn btn-block btn-info" style="width: 100px; margin-top: 10px;" data-toggle="modal" id="btnNew">Venta</button>
            </div>
          </div><!-- /.row -->
        </section><!-- /.content -->
      
      <script>
        $(function () {

        $('#btnAdd').click(function() {
             //Cargar clientes
             //Elegir fecha y hora(de mañana para adelante )
             //Registrar visita
            LoadIconAction('modalAction','Program');
            WaitingOpen('Cargando Clientes');
              $.ajax({
                    type: 'POST',
                    data: null,
                    url: 'index.php/dash/getCustommers', 
                    success: function(result){
                                  WaitingClose();
                                  $("#modalBodyProgrammer").html(result.html);
                                  $('#vstFecha').datepicker({minDate: '0'});
                                  setTimeout("$('#modalProgrammer').modal('show');",800);
                                  $(".select2").select2();
                          },
                    error: function(result){
                          WaitingClose();
                          alert("error");
                        },
                    dataType: 'json'
                });
         });

        var pedido = [];

        $('#btnNew').click(function(){
            pedido = [];
            LoadIconAction('modalAction_4','Add');
            WaitingOpen('Cargando Datos');
            $.ajax({
                    type: 'POST',
                    data: null,
                    url: 'index.php/dash/getSaleData', 
                    success: function(result){
                                  WaitingClose();
                                  $("#modalBodySale").html(result.html);
                                  setTimeout("$('#modalSale').modal('show');",800);
                                  $(".select2").select2();
                                  $('#cliId_s').change(function(){
                                    //Saldo
                                    var importe = $(this).children('option:selected').data('balance');
                                    if(importe < 0){
                                      $('#textBalance').html('<i class="fa fa-fw fa-plus" style="color: #00a65a"></i>');
                                      $('#importBalance').html(importe.replace('-',''));
                                    }
                                    else{
                                      if(importe == 0){
                                        $('#textBalance').html('<i class="fa fa-fw fa-check" style="color: #3c8dbc"></i>');
                                        $('#importBalance').html(importe);
                                      }
                                      else{
                                        $('#textBalance').html('<i class="fa fa-fw fa-minus" style="color: #dd4b39"></i>');
                                        $('#importBalance').html(importe);
                                      }
                                    }

                                    //Dni
                                    $('#dniNumber').html($(this).children('option:selected').data('dni'));

                                    //Address
                                    $('#address').html($(this).children('option:selected').data('address'));
                                  });
                                  $('#prodId_s').change(function(){
                                      //price
                                      var price = $(this).children('option:selected').data('price');
                                      var margin = $(this).children('option:selected').data('margin');
                                      var coste = parseFloat(price) + (parseFloat(price) * (parseFloat(margin) / 100));
                                      $('#prodPrice').html(coste.toFixed(2));
                                  });
                                  //Fecha
                                  $('#vstFecha__s').datepicker({minDate: '0'});
                                  $('#addProduct').click(function(){
                                    //validar

                                    //Agregar objeto
                                    var band = false;
                                    for( var i = 0 ; i < pedido.length ; i++ ){
                                      if(pedido[i]['prodId'] == $('#prodId_s').val()){
                                        band = true;
                                        pedido[i]['prodCant'] += parseInt($('#cant_').val());
                                      }
                                    }

                                    if(band == false){
                                      var product = {
                                            'prodId'    : $('#prodId_s').val(),
                                            'prodDesc'  : $('#prodId_s option:selected').text(),
                                            'prodPrice' : $('#prodPrice').html(),
                                            'prodCant'  : parseInt($('#cant_').val())
                                      };
                                      pedido.push(product);
                                    }

                                    ArmarPedido();

                                    $('#cant_').val('1');
                                  });

                                  $('#sum').click(function(){
                                      var cant = parseInt($('#cant_').val());
                                      cant++;
                                      $('#cant_').val(cant);
                                  });

                                  $('#sub').click(function(){
                                      var cant = parseInt($('#cant_').val());
                                      cant--;
                                      if(cant <= 0){
                                        $('#cant_').val('1');
                                      } else {
                                        $('#cant_').val(cant);}
                                  });
                          },
                    error: function(result){
                          WaitingClose();
                          alert(result);
                        },
                    dataType: 'json'
                });
        });

        function ArmarPedido(){
          //Limpiar body de la tabla
          $('#products > tbody').html('');
          
          //Insertar todos los articulos nuevamente
          var rows = '';
          var total = 0;
          $('#total').html(parseFloat(total).toFixed(2));
          $.each(pedido, function(idx, obj){ 
              rows += '<tr>';
              rows += '<td><i class="fa fa-times remove" style="color: red" id="'+obj['prodId']+'"></i></td>';
              rows += '<td>'+obj['prodDesc']+'</td>';
              rows += '<td>'+obj['prodPrice']+'</td>';
              rows += '<td>'+obj['prodCant']+'</td>';
              var importe = parseInt(obj['prodCant']) * parseFloat(obj['prodPrice']).toFixed(2)
              rows += '<td>'+importe.toFixed(2)+'</td>';
              rows += '</tr>';
              total = parseFloat(total) + parseFloat(importe);
              $('#total').html(parseFloat(total).toFixed(2));
          });
          $("#products > tbody ").append(rows);

          $('.remove').click(function(e){

            var indice = 0;
            for( var i = 0 ; i < pedido.length ; i++ ){
              if(pedido[i]['prodId'] == $(this).get(0).id){
                indice = i;
                break;
              }
            }
            pedido.splice(indice,1);
            ArmarPedido();
          });
        }

        var reprogramIdVisit = 0;
        $('#btnReprogram').click(function(){
          reprogramIdVisit = 0;
          reprogramIdVisit = $('#vstId').val();
          LoadIconAction('modalAction_3','ReProgram');
          $('#modalPay').modal('hide');
          WaitingOpen('Cargando Clientes');
              $.ajax({
                    type: 'POST',
                    data: {
                            id: $('#vstId').val()
                        },  
                    url: 'index.php/dash/getCustommerReprogram', 
                    success: function(result){
                                  WaitingClose();
                                  $("#modalBodyReProgram").html(result.html);
                                  $('#vstFecha_').datepicker({minDate: '0'});
                                  setTimeout("$('#modalReprogram').modal('show');",800);
                          },
                    error: function(result){
                          WaitingClose();
                          alert(result);
                        },
                    dataType: 'json'
                });
        });
        $('#btnSave').click(function(){
            
            var hayError = false;
            if($('#cliId').val() == -1)
            {
              hayError = true;
            }

            if($('#vstFecha').val() == "")
            {
              hayError = true;
            }

            if(hayError == true){
              $('#error').fadeIn('slow');
              return;
            }

            WaitingOpen('Registrando Visita');
            $.ajax({
                  type: 'POST',
                  data: {
                          cliId: $('#cliId').val(),
                          dia: $('#vstFecha').val(),
                          hora: $('#vstHora').val(),
                          min: $('#vstMinutos').val(),
                          note: $('#vstNote').val()
                        },
                  url: 'index.php/dash/setVisit', 
                  success: function(result){
                                WaitingClose();
                                $('#modalProgrammer').modal('hide');
                                $('#calendar').fullCalendar('refetchEvents');

                                $("#modalResultValue").html(result);
                                setTimeout("$('#modalResult').modal('show');",1000);
                        },
                  error: function(result){
                        WaitingClose();
                        alert("error");
                      },
                  dataType: 'json'
              });

        });

        $("#btnSavePay").click(function(){

          $('#error_s').fadeOut('slow');
          var hayError = false;
            if($('#statusPrice').val() == "")
            {
              hayError = true;
            }

            if(hayError == true){
              $('#error_s').fadeIn('slow');
              return;
            }

            WaitingOpen('Registrando Pago');
            $.ajax({
                  type: 'POST',
                  data: {
                          cliId: $('#cliId').val(),
                          vstId: $('#vstId').val(),
                          price: $('#statusPrice').val(),
                          note: $('#statusNote').val()
                        },
                  url: 'index.php/cash/setPay', 
                  success: function(result){
                                WaitingClose();
                                $('#modalPay').modal('hide');
                                $('#calendar').fullCalendar('refetchEvents');

                                $("#modalResultValue").html(result);
                                setTimeout("$('#modalResult').modal('show');",1000);
                        },
                  error: function(result){
                        WaitingClose();
                        alert("error");
                      },
                  dataType: 'json'
              });
        });

        $("#btnVisted").click(function(){

            WaitingOpen('Registrando Operación');
            $.ajax({
                  type: 'POST',
                  data: {
                          vstId: $('#vstId').val()
                        },
                  url: 'index.php/dash/cancelVisit', 
                  success: function(result){
                                WaitingClose();
                                $('#modalPay').modal('hide');
                                $('#calendar').fullCalendar('refetchEvents');

                                $("#modalResultValue").html(result);
                                setTimeout("$('#modalResult').modal('show');",1000);
                        },
                  error: function(result){
                        WaitingClose();
                        alert("error");
                      },
                  dataType: 'json'
              });
        });

        $('#btnSaveReprogram').click(function(){
            var hayError = false;
            if($('#vstFecha_').val() == "")
            {
              hayError = true;
            }

            if(hayError == true){
              $('#error').fadeIn('slow');
              return;
            }
            
            WaitingOpen('Reprogramando Visita');
            $.ajax({
                  type: 'POST',
                  data: {
                          dia: $('#vstFecha_').val(),
                          hora: $('#vstHora_').val(),
                          min: $('#vstMinutos_').val(),
                          note: $('#vstNote_').val(),
                          vstId: reprogramIdVisit
                        },
                  url: 'index.php/dash/setReprogramVisit', 
                  success: function(result){
                                WaitingClose();
                                $('#modalReprogram').modal('hide');
                                $('#calendar').fullCalendar('refetchEvents');

                                $("#modalResultValue").html(result);
                                setTimeout("$('#modalResult').modal('show');",1000);
                        },
                  error: function(result){
                        WaitingClose();
                        alert(result);
                      },
                  dataType: 'json'
              });
        });

        $('#btnSaveSale').click(function(){
          var hayError = false;
            
            if($('#cliId_s').val() == -1)
            {
              hayError = true;
            }

            if(pedido.length <= 0){
              hayError = true;
            }

            if($('#vstFecha__s').val() == ''){
              hayError = true; 
            }

            if(hayError == true){
              $('#error_s').fadeIn('slow');
              setTimeout("$('#error_s').fadeOut('slow');",3000);
              return;
            }

            var aCuenta = 0;
            if($('#to_acount').val() != ''){
              aCuenta = parseFloat($('#to_acount').val());
            }
            WaitingOpen('Registrando Compra');

            $.ajax({
                  type: 'POST',
                  data: {
                          cliId: $('#cliId_s').val(),
                          ped: pedido,
                          aCuent: aCuenta,
                          fecha: $('#vstFecha__s').val(),
                          hora: $('#vstHora__s').val(),
                          min: $('#vstMinutos__s').val(),
                          note: $('#vstNote__s').val()
                        },
                  url: 'index.php/dash/setSale', 
                  success: function(result){
                                WaitingClose();
                                $('#modalSale').modal('hide');
                                $('#calendar').fullCalendar('refetchEvents');
                        },
                  error: function(result){
                        WaitingClose();
                        alert(result);
                      },
                  dataType: 'json'
              });
          //alert("venta");
        });
        /* initialize the external events
         -----------------------------------------------------------------*/
        function ini_events(ele) {
          ele.each(function () {

            // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
            // it doesn't need to have a start or end
            var eventObject = {
              title: $.trim($(this).text()) // use the element's text as the event title
            };

            // store the Event Object in the DOM element so we can get to it later
            $(this).data('eventObject', eventObject);

            // make the event draggable using jQuery UI
            $(this).draggable({
              zIndex: 1070,
              revert: true, // will cause the event to go back to its
              revertDuration: 0  //  original position after the drag
            });

          });
        }
        ini_events($('#external-events div.external-event'));

        /* initialize the calendar
         -----------------------------------------------------------------*/
        //Date for the calendar events (dummy data)
        var date = new Date();
        var d = date.getDate(),
            m = date.getMonth(),
            y = date.getFullYear();

        $('#calendar').fullCalendar({
          header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
          },
          buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            day: 'Día'
          },
          //Load events pending
          events: function(start, end, timezone, callback) {
                  WaitingOpen('Cargando Visitas');
                  var date_ = new Date($("#calendar").fullCalendar('getDate'));
                  $.ajax({
                      url: 'index.php/customer/visits',
                      data: { month: date_.getMonth() },
                      dataType: 'json',
                      type: 'POST',
                      success: function(doc) {
                          var events = [];
                          $(doc).each(function() {
                            var from = $(this).attr('vstDate');
                            var to = new Date(from);
                            to.setMinutes ( to.getMinutes() + 30 );
                              events.push({
                                  title: $(this).attr('cliLastName') + ',' + $(this).attr('cliName'),
                                  start: $(this).attr('vstDate'), // will be parsed
                                  end: to,
                                  id: $(this).attr('vstId'),
                                  allDay: false,
                                  backgroundColor: $(this).attr('cliColor'),
                                  borderColor: 'black'
                              });
                          });
                          callback(events);
                          WaitingClose();
                      },
                      error: function(doc) {
                        WaitingClose();
                        alert("Error:" + doc);
                      }
                  });
              },
          eventClick: function(event) {
              //alert("ok" + event.id);
              WaitingOpen('Cargando Cliente');
              $.ajax({
                      url: 'index.php/customer/status',
                      data: { id: event.id },
                      dataType: 'json',
                      type: 'POST',
                      success: function(result) {
                          $("#modalBodyPay").html(result.html);
                          setTimeout("$('#modalPay').modal('show')",800);
                          WaitingClose();
                      },
                      error: function(doc) {
                        WaitingClose();
                        alert("Error:" + doc);
                      }
                  });
          },

          editable: true,
          droppable: true, // this allows things to be dropped onto the calendar !!!
          drop: function (date, allDay) { // this function is called when something is dropped

            // retrieve the dropped element's stored Event Object
            var originalEventObject = $(this).data('eventObject');

            // we need to copy it, so that multiple events don't have a reference to the same object
            var copiedEventObject = $.extend({}, originalEventObject);

            // assign it the date that was reported
            copiedEventObject.start = date;
            copiedEventObject.allDay = allDay;
            copiedEventObject.backgroundColor = $(this).css("background-color");
            copiedEventObject.borderColor = $(this).css("border-color");

            // render the event on the calendar
            // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
            $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

            // is the "remove after drop" checkbox checked?
            if ($('#drop-remove').is(':checked')) {
              // if so, remove the element from the "Draggable Events" list
              $(this).remove();
            }

          }
        });

        /* ADDING EVENTS */
        var currColor = "#3c8dbc"; //Red by default
        //Color chooser button
        var colorChooser = $("#color-chooser-btn");
        $("#color-chooser > li > a").click(function (e) {
          e.preventDefault();
          //Save color
          currColor = $(this).css("color");
          //Add color effect to button
          $('#add-new-event').css({"background-color": currColor, "border-color": currColor});
        });
        $("#add-new-event").click(function (e) {
          e.preventDefault();
          //Get value and make sure it is not null
          var val = $("#new-event").val();
          if (val.length == 0) {
            return;
          }

          //Create events
          var event = $("<div />");
          event.css({"background-color": currColor, "border-color": currColor, "color": "#fff"}).addClass("external-event");
          event.html(val);
          $('#external-events').prepend(event);

          //Add draggable funtionality
          ini_events(event);

          //Remove event from text input
          $("#new-event").val("");
        });
      });

      </script>

<!-- Modal -->
<div class="modal fade" id="modalProgrammer" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span id="modalAction"> </span> Visita</h4> 
      </div>
      <div class="modal-body" id="modalBodyProgrammer">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnSave">Guardar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalPay" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><span id="modalAction_2"><i class="fa fa-fw fa-money" style="color: green;"></i></span>  Registar Pago</h4> 
      </div>
      <div class="modal-body" id="modalBodyPay">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger pull-left" id="btnVisted">Visitado</button>
        <button type="button" class="btn btn-success pull-left" id="btnReprogram">Reprogramar</button>
        <!--<button type="button" class="btn btn-warning pull-left" id="btnReProgram">Reprogramar</button>-->
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnSavePay">Guardar</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="modalResult" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><span><i class="fa fa-fw fa-bell" style="color: #f39c12 !important"></i></span> Resultado</h4> 
      </div>
      <div class="modal-body" id="modalBodyResult" style="text-align:center;">
        <h3 id="modalResultValue"></h3>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalReprogram" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><span id="modalAction_3"></span>  Visita</h4> 
      </div>
      <div class="modal-body" id="modalBodyReProgram">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnSaveReprogram">Guardar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalSale" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><span id="modalAction_4"></span>  Venta</h4> 
      </div>
      <div class="modal-body" id="modalBodySale">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnSaveSale">Guardar</button>
      </div>
    </div>
  </div>
</div>