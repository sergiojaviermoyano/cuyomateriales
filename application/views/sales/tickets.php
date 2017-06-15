<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
          <?php
          if ($data['cajaId'] == -1) {
            ?>
            <div class="box-header">
              <h3>No hay cajas abiertas para poder cobrar </h3>
            </div>
            <?php
          } else {
          ?>
            <div class="box-body">
              <table id="ventas_table" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th width="20%">Acciones</th>
                    <th width="10%">Número</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                  </tr>
                </thead>
                <tbody>
                  
                </tbody>
              </table>
            </div><!-- /.box-body -->
          <?php
          }
          ?>
      </div><!-- /.box -->
    </div><!-- /.col -->
  </div><!-- /.row -->
</section><!-- /.content -->
<script>
$(function () {

    var datatable_es={
    "sProcessing":     "Procesando...",
    "sLengthMenu":     "Mostrar _MENU_ registros",
    "sZeroRecords":    "No se encontraron resultados",
    "sEmptyTable":     "Ningún dato disponible en esta tabla",
    "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
    "sInfoPostFix":    "",
    "sSearch":         "Buscar:",
    "sUrl":            "",
    "sInfoThousands":  ",",
    "sLoadingRecords": "Cargando...",
    "oPaginate": {
        "sFirst":    "Primero",
        "sLast":     "Último",
        "sNext":     "Siguiente",
        "sPrevious": "Anterior"
    },
    "oAria": {
        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    }
};

    var dataTable= $('#ventas_table').DataTable({
      "processing": true,
      "serverSide": true,
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true,
      "language":datatable_es,
      'ajax':{
          "dataType": 'json',
          //"contentType": "application/json; charset=utf-8",
          "method": "POST",
          "url":'index.php/sale/listingSales',
          "dataSrc": function (json) {
            var output=[];
            var permission=$("#permission").val();
            permission= permission.split('-');
            $.each(json.data,function(index,item){
              var td_1="";
                  //td_1+='<i class="fa fa-fw fa-print" style="color: #A4A4A4; cursor: pointer; margin-left: 15px;" onclick="Print('+item.ocId+')"></i>';

              var td_2="";
                   td_2+= ('0000-000000' + item.venId).slice(-13);

              var date = new Date(item.venFecha);
              var month = date.getMonth() + 1;
              var td_3=("0"+date.getDate()).slice(-2)+'-'+("0"+ month).slice(-2)+'-'+("0"+date.getFullYear()).slice(-4)+' '+("0"+date.getHours()).slice(-2)+':'+("0"+date.getMinutes()).slice(-2);
              
              var td_4="";

              switch (item.venEstado) {
                case 'AC':{
                  td_4='<small class="label pull-left bg-green">Activa</small>';
                  break;
                }
                case 'IN':{
                  td_4='<small class="label pull-left bg-red">Inactiva</small>';
                  break;
                }
                case 'FA':{
                  td_4='<small class="label pull-left bg-blue">Facturada</small>';
                  break;
                }
                default:{
                  td_4='';
                  break;
                }
              }
              //for(i=0;i<=100000;i++){
                output.push([td_1,td_2,td_3,td_4]);

              //}

            });
            return output;
          },
          error:function(the_error){
            console.debug(the_error);
          }
        }

    });
  });
</script>