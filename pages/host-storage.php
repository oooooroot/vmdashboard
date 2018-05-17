<?php
session_start();
if (!isset($_SESSION['username'])){
  header('Location: ../index.php');
}
require('header.php');
require('navigation.php');
?>

<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Host</h3>
      </div>
    </div>
  </div>

  <div class="clearfix"></div>

  <div class="row">
    <div class="col-md-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>Host Storage: <?php echo $hn; ?></h2>

          <div class="clearfix"></div>
        </div>

        <div class="x_content">

          <div class="col-md-9 col-sm-9 col-xs-12">
            <?php
            $tmp = $lv->host_get_node_info();
            $ret = false;

            if (array_key_exists('action', $_GET)) {
              $name = $_GET['name'];
              if ($_GET['action'] == 'dumpxml')
                $ret = 'XML dump of node device <i>'.$name.'</i>:<br/><br/>'.htmlentities($lv->get_node_device_xml($name, false));
            }

            if ($ret){
              echo "<pre>$ret</pre>";
              echo "<br /><br />";
            }

            $tmp = $lv->get_node_device_cap_options();
            for ($i = 0; $i < sizeof($tmp); $i++) {
              if ($tmp[$i] == "storage"){
                echo "<h4>{$tmp[$i]}</h4>";
                $tmp1 = $lv->get_node_devices($tmp[$i]);
                echo "<div class='table-responsive'>" .
                  "<table class='table'>" .
                  "<tr>" .
                  "<th> Device name </th>" .
                  "<th> Identification </th>" .
                  "<th> Driver name </th>" .
                  "<th> Vendor </th>" .
                  "<th> Product </th>" .
                  "<th> Action </th>" .
                  "</tr>";

                for ($ii = 0; $ii < sizeof($tmp1); $ii++) {
                  $tmp2 = $lv->get_node_device_information($tmp1[$ii]);
                  $act = !array_key_exists('cap', $_GET) ? "<a href=\"?action={$_GET['action']}&amp;action=dumpxml&amp;name={$tmp2['name']}\">Dump configuration</a>" :
                    "<a href=\"?action=dumpxml&amp;cap={$_GET['cap']}&amp;name={$tmp2['name']}\">Dump configuration</a>";

                  $driver  = array_key_exists('driver_name', $tmp2) ? $tmp2['driver_name'] : 'None';
                  $vendor  = array_key_exists('vendor_name', $tmp2) ? $tmp2['vendor_name'] : 'Unknown';
                  $product = array_key_exists('product_name', $tmp2) ? $tmp2['product_name'] : 'Unknown';

                  if (array_key_exists('vendor_id', $tmp2) && array_key_exists('product_id', $tmp2))
                    $ident = $tmp2['vendor_id'].':'.$tmp2['product_id'];
                  else
                    $ident = '-';

                  echo "<tr>" .
                    "<td>{$tmp2['name']}</td>" .
                    "<td>$ident</td>" .
                    "<td>$driver</td>" .
                    "<td>$vendor</td>" .
                    "<td>$product</td>" .
                    "<td>$act</td>" .
                    "</tr>";

                }

                echo "</table></div>";

              }
            }

            ?>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
        <!-- /page content -->

<?php require('footer.php');?>
