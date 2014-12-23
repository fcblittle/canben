<!--STYLES START-->
<link rel="stylesheet" href="<?php echo $this->misc('css/bootstrap.min.css'); ?>" />
<link rel="stylesheet" href="<?php echo $this->misc('css/bootstrap-responsive.min.css'); ?>" />
<link rel="stylesheet" href="<?php echo $this->misc('css/fullcalendar.css'); ?>" />
<link rel="stylesheet" href="<?php echo $this->misc('css/uniform.css'); ?>" />
<link rel="stylesheet" href="<?php echo $this->misc('css/select2.css'); ?>" />
<link rel="stylesheet" href="<?php echo $this->misc('css/datepicker.css'); ?>" />
<link rel="stylesheet" href="<?php echo $this->misc('css/bootstrap-timepicker.min.css'); ?>" />
<link rel="stylesheet" href="<?php echo $this->misc('css/matrix-style.css'); ?>" />
<link rel="stylesheet" href="<?php echo $this->misc('css/matrix-media.css'); ?>" />
<link rel="stylesheet" href="<?php echo $this->misc('font-awesome/css/font-awesome.css'); ?>"/>
<link rel="stylesheet" href="<?php echo $this->misc('css/jquery.gritter.css'); ?>" />
<link rel="stylesheet" href="<?php echo $this->misc('libs/uploadify/uploadify.css'); ?>">
<link rel='stylesheet' href='<?php echo $this->misc('font/font.css'); ?>' type='text/css'>
<link rel='stylesheet' href='<?php echo $this->misc('css/jquery.datetimepicker.css'); ?>' type='text/css'>

<?php if ($styles): ?>
<?php foreach ($styles as $item): ?>
<link rel="stylesheet" type="text/css" href="<?php echo $this->misc($item) ?>" />
<?php endforeach ?>
<?php endif ?>
<!--STYLES END-->