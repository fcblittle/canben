<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>404</title>
<link rel="stylesheet" href="/misc/app/styles/style.css" />
<link rel="stylesheet" href="/misc/app/styles/app.ui.css" />
</head>

<body>
 <div id="maincontent">
        <div class="wrap">
        <div class="main http-error http-error-404">
            <h3>哇哦~您无法浏览此内容：</h3>
            <p><?php echo isset($message) ? $message : '请检查您的权限是否适合此内容。' ?></p>
            <span class="code">403</span?>
        </div>
        </div>
    </div>
</body>
</html>