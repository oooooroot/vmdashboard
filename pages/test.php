<?php
require('header.php');

$uuid = "aaa9693e-79dd-4f14-9240-98b30c16b5b2";
$uuid = "ce016179-af13-45a3-880e-b81316d78f4c";
$uuid = "1cd47203-b1ff-449d-9df9-3d0818916407";
$domName = $lv->domain_get_name_by_uuid($uuid);
$dom = $lv->get_domain_object($domName);
$ret = $lv->domain_get_memory_stats($domName);

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
          var_dump($ret);
          echo "<br><br><br><br>";
          foreach ($ret as $key => $val) {
            echo $key . ": " . $val . "<br>";
          }
          echo "<br><br><br><br>";
          echo "Total memory: " . $ret[5]/1024 . " MB<br>";
          echo "Free memory: " . $ret[4]/1024 . " MB<br>";
          echo "Used memory: " . ($ret[5] - $ret[4])/1024 . " MB<br>";
          echo "Percent used: " . (1- $ret[4]/$ret[5]) * 100 . "% <br>";
          echo "Swap memory used by domain: " . $ret[1] . " KB<br>";
          echo "Usable memory for domain: " . $ret[5] . " KB<br>";
          echo "Assigned memory for domain: " . $ret[6] . " KB<br>";

          $cpuinfo = shell_exec("virsh domstats --cpu-total openstack");
          var_dump($cpuinfo);
          echo "<br><br><br><br>";

          $cpuinfo_0 = shell_exec("virsh domstats --cpu-total openstack");
          for ($i = 0; $i < 1; $i++) {
            sleep(1);
            $cpuinfo_1 = shell_exec("virsh domstats --cpu-total openstack");
          }
          $cpuexplode0 = explode(" ", $cpuinfo_0);
          echo $cpuexplode0[3] . "<br>";
          $cputime0 = explode("=", $cpuexplode0[3]);
          echo $cputime0[1] . "<br>";

          $cpuexplode1 = explode(" ", $cpuinfo_1);
          echo $cpuexplode1[3] . "<br>";
          $cputime1 = explode("=", $cpuexplode1[3]);
          echo $cputime1[1] . "<br>";

          $cpu_percentage = ($cputime1[1] - $cputime0[1])/2400000000*100;
          echo $cpu_percentage . "<br>";




          ?>




<div class="col-md-4 col-sm-4 col-xs-12">
  <div class="x_panel tile fixed_height_320">
    <div class="x_title">
      <h2>Console</h2>
      <div class="clearfix"></div>
    </div>
    <div class="x_content">
      <div class="col-md-4 col-sm-4 col-xs-12 profile_left">
        <div class="profile_img">
          <div id="crop-avatar">
            <!-- Current avatar -->
            <?php
            if ($state == "running") {
              //screenshot will get raw png data at 300 pixels wide
              $screenshot = $lv->domain_get_screenshot_thumbnail($_GET['uuid'], 400);
              //the raw png data needs to be encoded to use with html img tag
              $screen64 = base64_encode($screenshot['data']);
              ?>
              <a href="<?php echo $url; ?>:6080/vnc.html?path=?token=<?php echo $uuid ?>" target="_blank">
              <img src="data:image/png;base64,<?php echo $screen64 ?>" width="300px"/>
              </a>
              <?php
            } else if ($state == "paused") {
              echo "<img src='../assets/img/paused.png' width='300px' >";
            } else {
              echo "<img src='../assets/img/shutdown.png' width='300px' >";
            }
            ?>
        <!--    <img class="img-responsive avatar-view" src="images/picture.jpg" alt="Avatar" title="Change the avatar"> -->
          </div>
        </div>
      </div>

    </div>
  </div>
</div>


<div class="col-md-4 col-sm-4 col-xs-12">
  <div class="x_panel tile fixed_height_320">
    <div class="x_title">
      <h2>Console</h2>
      <div class="clearfix"></div>
    </div>
    <div class="x_content">
      <div class="dashboard-widget-content">
        <ul class="quick-list">
          <li><i class="fa fa-calendar-o"></i><a href="#">Settings</a></li>
          <li><i class="fa fa-bars"></i><a href="#">Subscription</a></li>
          <li><i class="fa fa-bar-chart"></i><a href="#">Auto Renewal</a> </li>
          <li><i class="fa fa-line-chart"></i><a href="#">Achievements</a></li>
          <li><i class="fa fa-bar-chart"></i><a href="#">Auto Renewal</a> </li>
          <li><i class="fa fa-line-chart"></i><a href="#">Achievements</a></li>
          <li><i class="fa fa-area-chart"></i><a href="#">Logout</a></li>
        </ul>

        <div class="sidebar-widget">
          <h4>Profile Completion</h4>
          <canvas width="150" height="80" id="chart_gauge_01" class="" style="width: 160px; height: 100px;"></canvas>
          <div class="goal-wrapper">
            <span id="gauge-text" class="gauge-value pull-left">0</span>
            <span class="gauge-value pull-left">%</span>
            <span id="goal-text" class="goal-value pull-right">100%</span>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>













          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /page content -->

<?php
require('footer.php');
?>
