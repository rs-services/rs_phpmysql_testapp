<!DOCTYPE html>

<html>

<head>
 <meta http-equiv="Content-type" content="text/html;charset=UTF-8">
 <title><?php if (function_exists('gethostname')) { echo gethostname(); } else { php_uname("n"); } ?> - PHP/MySQL Test Application</title>
 <link rel="stylesheet" type="text/css" media="screen" href="css/screen.css" />
</head>

<body>

<div id="header">
 <img src="http://www.rightscale.com/images/logo.gif" alt="" />
 <h1>PHP/MySQL Test Application</h1>
 <ul class="menu">
  <li><a style="border-left:solid 20px white" href="#host"><span>Host</span></a></li>
  <li><a href="php.php"><span>PHP</span></a></li>
  <li><a href="mysql.php"><span>MySQL</span></a></li>
  <li><a href="phpinfo.php"><span>phpinfo()</span></a></li>
  <li><a href="phpcredits.php"><span>phpcredits()</span></a></li>
  <li><a href="helloworld.php"><span>Hello World</span></a></li>
 </ul>
</div>

<div id="content">
