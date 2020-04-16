        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-body no-padding">
                  <!-- THE CALENDAR -->
                  <div id="calendar"></div>
                </div><!-- /.box-body -->
              </div><!-- /. box -->
            </div><!-- /.col -->
            <!--<div class="col-md-2">
              <button class="btn btn-block btn-success" style="width: 100px; margin-top: 10px;" data-toggle="modal" id="btnAdd">Programar</button>
              <button class="btn btn-block btn-info" style="width: 100px; margin-top: 10px;" data-toggle="modal" id="btnNew">Venta</button>
            </div>-->
          </div><!-- /.row -->
        </section><!-- /.content -->
      
      <script>
        $(function () {

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
            //right: 'month,agendaWeek,agendaDay'
          },
          buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            day: 'Día'
          },
          //Load events pending
          events: function(start, end, timezone, callback) {
                  WaitingOpen('Cargando Vencimientos');
                  var date_ = new Date($("#calendar").fullCalendar('getDate'));
                  $.ajax({
                      url: 'index.php/check/vencimientos',
                      data: { month: date_.getMonth() },
                      dataType: 'json',
                      type: 'POST',
                      success: function(doc) {
                          var events = [];
                          $(doc).each(function() {
                            var from = $(this).attr('cheVencimiento');
                            var to = new Date(from);
                            to.setMinutes ( to.getMinutes() + 30 );
                            events.push({
                                title: 'Nro: ' + $(this).attr('cheNumero') + '\n Banco: ' + $(this).attr('bancoDescripcion') + '\n Importe: $' + $(this).attr('cheImporte') + '\n Estado: ' + ($(this).attr('cheEstado') == 'AC' ? 'Activo' : ($(this).attr('cheEstado') == 'DE' ? 'Depositado' :'Utilizado')),
                                start: $(this).attr('cheVencimiento'), // will be parsed
                                end: $(this).attr('cheVencimiento'),
                                id: $(this).attr('cheId'),
                                allDay: true,
                                backgroundColor: ($(this).attr('cheEstado') == 'AC' ? '#00a65a' : ($(this).attr('cheEstado') == 'DE' ? '#f39c12' : '#0073b7') ),
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