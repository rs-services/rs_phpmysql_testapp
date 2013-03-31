<?php require_once("header.php.inc"); ?>

<h2 id="host">Host</h2>

<table>
 <tr>
  <th>Operating System</th><td><?php echo PHP_OS; ?></td>
 </tr>
 <tr>
  <th>Hostname</th><td><?php if (function_exists('gethostname')) { echo gethostname(); } else { php_uname("n"); } ?></td>
 </tr>
 <tr>
  <th>uname</th><td><?php echo php_uname(); ?></td>
 </tr>
 <tr>
  <th>LSB</th><td><pre><?php echo shell_exec("lsb_release -i -d -r -c"); ?></pre></td>
 </tr>
</table>

<?php require_once("footer.php.inc"); ?>