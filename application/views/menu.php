      <aside class="main-sidebar">
        <section class="sidebar">
          <ul class="sidebar-menu">
            <li class="treeview active">
              <a href="#"> <!-- onClick="cargarView('dash', 'calendar', '')"-->
                <i class="fa fa-dashboard"></i> <span>Escritorio</span> 
              </a>
            </li>
            <?php 
              
               foreach ($menu as $m) {
                  if(count($m['childrens']) > 0) {
                    echo '<li class="treeview">
                            <a href="#">
                              <i class="'.$m[0]['menuIcon'].'"></i> <span>'.$m[0]['menuName'].'</span>
                              <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">';
                            foreach ($m['childrens'] as $ch) {
                              $actions = "";
                              foreach ($ch['actions'] as $a) {
                                if($a['grpactId'] != null)
                                  $actions .= $a['actDescription'] .'-';
                              }
                              echo '<li>
                                      <a href="#" onClick="cargarView(\''.$ch['menuController'].'\',\''.$ch['menuView'].'\', \''.$actions.'\')">
                                        <i class="'.$ch['menuIcon'].'"></i> '.str_replace("_", " ", $ch['menuName']).' 
                                      </a>
                                    </li>';
                            }
                    echo '</ul>
                        </li>';
                  }
                  else
                  {
                    $actions = "";
                    foreach ($m['actions'] as $a) {
                      if($a['grpactId'] != null)
                        $actions .= $a['actDescription'] .'-';
                    }
                    echo '<li class="treeview">
                            <a href="#" onClick="cargarView(\''.$m['menuController'].'\',\''.$m['menuView'].'\', \''.$actions.'\')">
                              <i class="'.$m['menuIcon'].'"></i> <span>'.str_replace("_", " ", $m['menuName']).'</span>
                            </a>
                          </li>';
                  }
                } 
                
            ?>
        </ul>
      </section>
    </aside> 

      <script>
      function cargarView(controller, metodh, actions)
      {
        WaitingOpen();
        $('#content').empty();

        $.ajax({
            type: 'POST',
            //data: null,
            url: '<?php echo base_url(); ?>index.php/'+controller+'/'+metodh+'/'+actions, 
            success: function(result){
                          WaitingClose();
                          $("#content").html(result);
                  },
            error: function(result){
                  WaitingClose();
                  ProcesarError(result.responseText, 'modalOper');
                },
                dataType: 'json'
            });
      }
      </script>