<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Saldos Cuenta Corriente</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <div class="col-xs-8">
                    <div class="box-body">
                    <table id="credit" class="table table-bordered table-hover">
                        <thead>
                        <tr style="background-color: #A4A4A4">
                            <th style="text-align:center">Cliente</th>
                            <th style="text-align:center">Saldo</th>
                            <th style="text-align:center">Último Movimiento</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php 
                        $debe = 0;
                        $haber = 0;
                        $total = 0;
                        if(count($list) > 0) {  
                                foreach ($list as $s) {
                                    if($s['saldo'] > 0){
                                        echo '<tr>';
                                        //echo '<td style="text-align:center">'.date_format(date_create($m['cctepFecha']), 'd-m-Y').'</td>';
                                        echo '<td>'.$s['cliApellido'].' '.$s['cliNombre'].'</td>';
                                        echo '<td style="text-align:right">'.number_format ( $s['saldo'] , 2 , "," , "." ).'</td>';
                                        $total += $s['saldo'];
                                        echo '<td style="text-align:center">'.date_format(date_create($s['ultimo']), 'd-m-Y H:i').'</td>';
                                        echo '</tr>'; 
                                    }
                                }
                                echo '<tr>';
                                echo '<td style="text-align:right; font-size: 25px;">Total : </td>';
                                echo '<td style="text-align:right; font-size: 25px;">'.number_format ( $total, 2 , "," , "." ).'</td>';
                                echo '<td style="text-align:center"></td>';
                                echo '</tr>';  
                            }
                        ?>
                        </tbody>
                    </table>
                    </div>
                </div>

                <div class="col-xs-2 pull-right">
                    <h1 title="Imprimir Saldos"><i class="fa fa-fw fa-print" style="color: #A4A4A4; cursor: pointer; margin-left: 15px;" onclick="printSaldos()"></i></h1>
                </div>
            </div>
        </div>
    </div>
  </div>
</section>

<div class="modal fade" id="modalPrint" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="width: 50%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel__"><span id="modalAction__p"> </span> Comprobante</h4>
      </div>
      <div class="modal-body" id="modalBodyPrint">
        <div>
          <iframe style="width: 100%; height: 600px;" id="printDoc" src=""></iframe>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>

<script>
  function printSaldos(){
    WaitingOpen('Generando reporte...');
    LoadIconAction('modalAction__p','Print');
    $.ajax({
            type: 'POST',
            data: null,
        url: 'index.php/cuentacorriente/printSaldo',
        success: function(result){
                      WaitingClose();
                      var url = "./assets/reports/" + result;
                      $('#printDoc').attr('src', url);
                      setTimeout("$('#modalPrint').modal('show')",800);
              },
        error: function(result){
              WaitingClose();
              ProcesarError(result.responseText, 'modalPrint');
            },
            dataType: 'json'
        });
  }
</script>