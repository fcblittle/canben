<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title ?: '', ' - ', $siteName ?></title>
<?php $this->region('Module\Home:styles') ?>
<?php $this->region('Module\Home:scripts') ?>
</head>

<body id="<?php echo strtolower($module . '-' . $controller . '-' . $action) ?>">
<?php echo $messages ?>
 <div id="frame">
    <div class="wrapper">
<!--EMBED START-->
<?php $this->region($embedTemplate, $embedVars, $embedTheme) ?>
<!--EMBED END-->
    </div>
</div>
</body>
</html>