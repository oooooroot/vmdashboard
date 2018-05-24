<?php
// If the SESSION has not started, start it now
if (!isset($_SESSION)) {
    session_start();
}
// If there is no username, then we need to send them to the login
if (!isset($_SESSION['username'])){
  header('Location: login.php');
}
// We are now going to grab any POST data and put in in SESSION data, then clear it.
// This will prevent and reloading the webpage to resubmit and action.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['action'] = $_POST['action'];
    $_SESSION['name'] = $_POST['name'];
    unset($_POST);
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}
// Time to bring in the header, sidebar, and navigation menus
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
          <h2>Host: <?php echo $hn; ?></h2>

          <div class="clearfix"></div>
        </div>

        <div class="x_content">

          <div class="col-md-9 col-sm-9 col-xs-12">
            <?php
            // Time to get all information on the host
            $tmp = $lv->host_get_node_info();
            // Let's start the $ret without any data, it will be used to display returned XML info
            $ret = false;

            // Check for SESSION variables to see if we need to retieve XML data
            if (array_key_exists('action', $_SESSION)) {
              $name = $_SESSION['name'];
              if ($_SESSION['action'] == 'dumpxml')
                $ret = 'XML dump of node device <i>'.$name.'</i>:<br/><br/>'.htmlentities($lv->get_node_device_xml($name, false));
                //Unset the SESSION variables
                unset($_SESSION['name']);
                unset($_SESSION['action']);
            }

            //If we have returned XML data, display it
            if ($ret){
              echo "<pre>$ret</pre>";
              echo "<br /><br />";
            }

            //Time to retrieve the information about the host and place it in a table
            $tmp = $lv->get_node_device_cap_options();
            for ($i = 0; $i < sizeof($tmp); $i++) {

              //Just pull out NET data
              if ($tmp[$i] == "net"){
                echo "<h4>{$tmp[$i]}</h4>";
                $tmp1 = $lv->get_node_devices($tmp[$i]);
                echo "<div class='table-responsive'>" .
                  "<table class='table'>" .
                  "<tr>" .
                  "<th> Device name </th>" .
                  "<th> Interface </th>" .
                  "<th> Driver name </th>" .
                  "<th> MAC Address </th>" .
                  "<th> Product </th>" .
                  "<th> Action </th>" .
                  "</tr>";

                for ($ii = 0; $ii < sizeof($tmp1); $ii++) {
                  $tmp2 = $lv->get_node_device_information($tmp1[$ii]);
                  //Actions will be a form button that will submit info using POST
                  $act = "<form method=\"post\" action=\"\">
                    <input type=\"hidden\" name=\"action\" value=\"dumpxml\">
                    <input type=\"hidden\" name=\"name\" value=\"{$tmp2['name']}\">
                    <input type=\"submit\" name=\"submit\" value=\"XML\">
                    </form>";

                  $interface = array_key_exists('interface_name', $tmp2) ? $tmp2['interface_name'] : '-';
                  $driver = array_key_exists('capabilities', $tmp2) ? $tmp2['capabilities'] : '-';
                  $mac_address = array_key_exists('address', $tmp2) ? $tmp2['address'] : '-';
                  $product = array_key_exists('link', $tmp2) ? $tmp2['link'] : '-';

                  echo "<tr>" .
                    "<td>{$tmp2['name']}</td>" .
                    "<td>$interface</td>" .
                    "<td>$driver</td>" .
                    "<td>$mac_address</td>" .
                    "<td>$product</td>" .
                    "<td>$act</td>" .
                    "</tr>";

                    var_dump($tmp2);

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
