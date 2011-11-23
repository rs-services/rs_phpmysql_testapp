<!DOCTYPE html>

<html>

<head>
 <meta http-equiv="Content-type" content="text/html;charset=UTF-8">
 <title>Simple PHP/MySQL Test Application</title>
 <link rel="stylesheet" type="text/css" media="screen" href="css/screen.css" />
</head>

<body>

<div id="header">
 <img src="http://www.rightscale.com/images/logo.gif" alt="" />
 <h1>PHP/MySQL Test Application</h1>
 <ul class="menu">
  <li><a style="border-left:solid 20px white" href="#host"><span>Host</span></a></li>
  <li><a href="#mysql"><span>MySQL</span></a></li>
  <li><a href="#php"><span>PHP</span></a></li>
  <li><a href="phpinfo.php"><span>phpinfo()</span></a></li>
  <li><a href="phpcredits.php"><span>phpcredits()</span></a></li>
  <li><a href="helloworld.php"><span>Hello World</span></a></li>
 </ul>
</div>

<div id="content">

<h2 id="host">Host</h2>

<table>
 <tr>
  <th>Operating System</th><td><?php echo PHP_OS; ?></td>
 </tr>
 <tr>
  <th>Hostname</th><td><?php echo gethostname(); ?></td>
 </tr>
 <tr>
  <th>uname</th><td><?php echo php_uname(); ?></td>
 </tr>
</table>

<h2 id="mysql">MySQL</h2>

<h3>Connectivity</h3>

<?php

$db_config = 'config/db.php';

// include database configuration set from DB configure database RightScript
if (!file_exists($db_config))
{
	echo "<p>Database configuration file missing: <code>$db_config</code> does not exist within <code>" . getenv("DOCUMENT_ROOT") . "</code>.<br />\nPlease install this file first before continuing.</p>\n"	;
}
include $db_config;

// include app functions
include 'functions.php';

// determine best mysql driver to use
//print_r(get_loaded_extensions()); exit;
if (extension_loaded('pdo_mysql'))
{
	$mysql_driver = 'pdo_mysql';
	// try PDO
	do_pdo('mysql', $hostname_DB, $database_DB, 'UTF-8', $username_DB, $password_DB, "SELECT * FROM `phptest`;");
}
elseif (extension_loaded('mysqli'))
{
	$mysql_driver = 'mysqli';
	// use mysqli driver
    do_mysqli($hostname_DB, $username_DB, $password_DB, $database_DB);
}
elseif (extension_loaded('mysql'))
{
	$mysql_driver = 'mysql';
	// fallback to old mysql driver
	do_mysql($hostname_DB, $username_DB, $password_DB, $database_DB, "SELECT * FROM `phptest`;");
}
else
{
	echo 'No pdo_mysql, mysqli or mysql extension loaded. Please load one in php.ini, then reload apache before continuing.';
}
?>	

<table>
 <tr>
  <th>Connection</th><td><?php echo $db_connect_result; ?></td>
 </tr>
 <tr>
  <th>Driver</th><td><?php echo $mysql_driver; ?></td>
 </tr>
<?php
// reconnect to get extra info via mysql extension
if (strpos($db_connect_result, 'Success'))
{
	$link = mysql_connect($hostname_DB, $username_DB, $password_DB);
	if ($link)
	{
		if (mysql_get_client_info())
		{
			printf("<tr><th>Client info</th><td>%s</td></tr>\n", mysql_get_client_info());
		}
		if (mysql_get_host_info())
		{
			printf("<tr><th>Host info</th><td>%s</td></tr>\n", mysql_get_host_info());
		}
		if (mysql_get_server_info())
		{
			printf("<tr><th>Server version</th><td>%s</td></tr>\n", mysql_get_server_info());
		}
		if (mysql_get_proto_info())
		{
			printf("<tr><th>Protocol version</th><td>%s</td></tr>\n", mysql_get_proto_info());
		}
	}
}
?>
</table>

<?php
if (strpos($db_connect_result, 'Successful'))
{
?>
<h3>Test Data</h3>
<h4>
 <em style="font-weight:normal">phptest</em>
</h4>
<?php
echo $db_data;
}
?>

<h2 id="php">PHP</h2>

<h3>Versions</h3>

<table>
 <tr>
  <th>PHP version</th><td><?php echo phpversion(); ?></td><td><?php echo '<img src="' . $_SERVER['PHP_SELF'] . '?=' . php_logo_guid() . '" alt="PHP Logo !" />'; ?></td>
 </tr>
 <tr>
  <th>Zend engine version</th><td><?php echo zend_version(); ?></td><td style="text-align:center"><?php echo '<img src="' . $_SERVER['PHP_SELF'] . '?=' . zend_logo_guid() . '" alt="Zend Logo !" />'; ?></td>
 </tr>
 <tr><th>SAPI interface</th><td colspan="2"><?php echo php_sapi_name(); ?></td></tr>
</table>

<h3>Loaded Extensions</h3>

<table>
<?php
foreach (get_loaded_extensions() as $i => $ext) 
{ 
	echo '<tr><th>' . $ext . '</th>'; 
	if (phpversion($ext))
	{
		echo '<td>' . phpversion($ext) . '</td>';
	}
	echo "</td></tr>\n"; 
} 
?>
</table>

<!--
<h3>Modules</h3>

<?php
//phpinfo(INFO_MODULES);
?>
-->

</div>

<div id="footer">
 <p>Copyright &copy; <a href="http://rightscale.com/">RightScale</a> Inc. 2011. License-free.<br />
 Page rendered on <?php print strftime("%a %d %b %H:%M:%S %Y %z"); ?>.
 </p>
</div>

</body>

</html>
