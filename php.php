<?php require_once("header.php.inc"); ?>

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
	echo " <tr>\n"; 
	echo '  <th>' . $ext . '</th>'; 
	if (phpversion($ext))
	{
		echo '<td>' . phpversion($ext) . "</td>\n";
	}
	else
	{
		echo '<td style="background:#eee">&nbsp;</td>' . "\n";
	}
	echo " </tr>\n"; 
} 
?>
</table>

<!--
<h3>Modules</h3>

<?php
//phpinfo(INFO_MODULES);
?>
-->

<?php require_once("footer.php.inc"); ?>