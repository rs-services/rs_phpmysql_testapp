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
// for PDO and mysql drivers, world db dump is assumed (country table)
if (extension_loaded('pdo_mysql'))
{
	$mysql_driver = 'pdo_mysql';
	// try PDO
	do_pdo('mysql', $hostname_DB, $database_DB, 'utf8', $username_DB, $password_DB, "SELECT * FROM `Country`;");
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
	do_mysql($hostname_DB, $username_DB, $password_DB, $database_DB, "SELECT * FROM `Country`;");
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
 <tr>
  <th>Driver</th><td><?php echo $mysql_driver; ?></td>
 </tr>
<?php
if (get_extension_funcs($mysql_driver))
{
?>
 <tr>
  <th>Driver Functions</th>
   <td>
    <ul>
  <?php
  foreach(get_extension_funcs($mysql_driver) as $function)
  {
  	echo "<li>$function</li>\n";
  }
  ?>
   </ul>
  </td>
 </tr>
<?php
}
?>
</table>

<?php
if (strpos($db_connect_result, 'Successful'))
{
?>
<h3>Test Data</h3>
<h4>
 <em style="font-weight:normal">world.Country</em>
</h4>
<div style="width:800px; height:200px; overflow-y:auto; overflow-x:auto; clip-rect:(0px, 800px, 800px, 0px)">
<?php
echo $db_data;
}
?>
</div>