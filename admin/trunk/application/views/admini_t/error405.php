<!DOCTYPE html>
<html lang="en">
<head>
<title>错误页</title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="<?php echo base_url()?>asset/css/bootstrap.min.css" />
<link rel="stylesheet" href="<?php echo base_url()?>asset/css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="<?php echo base_url()?>asset/css/matrix-style.css" />
<link rel="stylesheet" href="<?php echo base_url()?>asset/css/matrix-media.css" />
<link href="<?php echo base_url()?>asset/font-awesome/css/font-awesome.css" rel="stylesheet" />

</head>
<body>

<!--<div id="content">-->
  <div id="content-header">
    <div id="breadcrumb"> &nbsp;&nbsp;&nbsp; </div>
    <h1>Error 405</h1>
  </div>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
            <h5>Error 405</h5>
          </div>
          <div class="widget-content">
            <div class="error_ex">
              <h1>405</h1>
              <h3><?php echo $content;?></h3>
              <p>Access to this page is forbidden</p>
              <a class="btn btn-warning btn-big"  href="<?php echo base_url()?>">Back to Home</a> </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<!--</div>-->

<script src="<?php echo base_url()?>asset/js/jquery.min.js"></script>
<script src="<?php echo base_url()?>asset/js/jquery.ui.custom.js"></script>
<script src="<?php echo base_url()?>asset/js/bootstrap.min.js"></script>
<script src="<?php echo base_url()?>asset/js/maruti.html"></script>
</body>
</html>
