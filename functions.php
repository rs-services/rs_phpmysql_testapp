<?php

$db_data = false;

function get_trace($err)
{
	global $password_DB;	// for masking password if found in traceback
	
	$trace = '<table>';
	foreach ($err->getTrace() as $a => $b)
	{
		foreach ($b as $c => $d)
		{
			if ($c == 'args')
			{
				foreach ($d as $e => $f)
				{
					$f = str_replace($password_DB, '****', $f);
					$trace .= '<tr><td>' . strval($a) . '#</td><td align="right">args:</td><td>' . $e . ':</td><td>' . $f . '</td></tr>';
				}
			}
			else
			{
				$trace .= '<tr><td>' . strval($a) . '#</td><td align="right">' . $c . ':</td><td></td><td>' . $d . '</td>';
			}
		}
	}
	$trace .= '</table>';
	$return = '<fieldset style="display:inline"><legend>[PHP PDO Error ' . strval($err->getCode()) . '</legend><table><tr><td align="right">Message:</td><td><strong>' . $err->getMessage() . '</strong></td></tr><tr><td align="right">Code:</td><td>' . strval($err->getCode()) . '</td></tr><tr><td align="right">File:</td><td>' . $err->getFile() . '</td></tr><tr><td align="right">Line:</td><td>' . strval($err->getLine()) . '</td></tr><tr><td align="right">Trace:</td><td>' . $trace . '</td></tr></table></fieldset>';
	return $return;
}


function do_pdo($driver, $host, $db, $charset, $user, $password, $query)
{
	global $db_connect_result, $db_data;

	try
	{
		// create new pdo object
		$pdo = new PDO("$driver:host=$host;dbname=$db;charset=$charset", $user, $password);
		
		// Set Errorhandling to Exception
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		// get fields from table
		$q = $pdo->prepare("DESCRIBE phptest;");
		$q->execute();
		$table_fields = $q->fetchAll(PDO::FETCH_COLUMN);
			
		// get table data
		$q = $pdo->query($query);
		$results = $q->fetchAll(PDO::FETCH_ASSOC);
		
		// close the connection
		$pdo = null;
	}
	catch (PDOException $e)
	{
		$db_connect_result = '<p style="text-align:center"><img src="images/red-cross.png" style="margin-right:5px;margin-bottom:-3px; alt="" /><strong><span style="color:red">Connect failed!</span></strong></p>' . "\n	";
		$db_connect_result .= get_trace($e);
		return;
	}
	
	// create html data table
	$db_data .= "<table>\n";
   	$db_data .= " <tr>\n  ";
	// add table headers from cols
	foreach($table_fields as $field)
	{
		$db_data .= '<th>' . $field . '</th>';
	}
	$db_data .= "\n </tr>\n";
	// iterate through each record
   	foreach($results as $a)
	{
    	$db_data .= " <tr>\n  ";
        foreach($a as $v)
    	{
    		$db_data .= '<td>' . $v . '</td>';
    	}
    	$db_data .= "\n </tr>\n";
	}
 	$db_data .= "</table>\n";
 	
 	$db_data .= "<p>Returned " . $q->rowCount() . " rows for " . $q->columnCount() . " fields.</p>\n";
	
	$db_connect_result = '<img src="images/tick-clean.png" style="margin-right:5px;margin-bottom:-3px;" alt="" /><strong><span style="color:green">Successful</span></strong>';
}


function do_mysqli($host, $user, $password, $schema)
{
	global $db_connect_result, $db_data;
	
	$mysqli = new mysqli($host, $user, $password, $schema);
	
	/* check connection */
	if ($mysqli->connect_errno)
	{
		$db_connect_result = '<img src="images/red-cross.png" style="margin-right:5px;margin-bottom:-3px;" alt="" /><strong><span style="color:red">Connect failed</span></strong><br /><p>' . $mysqli->connect_error . "</p>\n";
	}
	else
	{
		$db_connect_result = '<img src="images/tick-clean.png" style="margin-right:5px;margin-bottom:-3px;" alt="" /><strong><span style="color:green">Successful</span></strong>';
	}
	/* Select queries return a resultset */
	if ($result = $mysqli->query("SELECT * FROM `phptest`;"))
	{
	   	$db_data .= "<table>\n";
	   	$db_data .= "<tr>\n";
        while ($field = $result->fetch_field()) $db_data .= "<th>".$field->name."</th>";
        $db_data .= "</tr>\n";
   		while ($linea = $result->fetch_assoc())
   		{
        	$db_data .= "<tr>\n";
        	foreach ($linea as $valor_col) $db_data .= '<td>'.$valor_col.'</td>';
        	$db_data .= "</tr>\n";
    	}
   		$db_data .= "</table>\n";
   		$db_data .= "<p>Returned " . $result->num_rows . " rows for " . $result->field_count . " fields.</p>\n";
   		
    	/* free result set */
		$result->close();
	}
}


function do_mysql($host, $user, $password, $database, $query)
{
	global $db_connect_result, $db_data;

	// connect to db server
	$link = mysql_connect($host, $user, $password);
	if (!$link)
	{
    	$db_connect_result .= '<p><img src="images/red-cross.png" style="margin-right:5px;margin-bottom:-3px;" alt="" /><strong><span style="color:red">Connection failed!</span></strong></p><p>' . mysql_error() . "</p>\n";
    	return;
	}
	else
	{
		// select database
		$db = mysql_select_db($database);
		if (!$db)
		{
			$db_connect_result = '<p><img src="images/red-cross.png" style="margin-right:5px;margin-bottom:-3px;" alt="" /><strong><span style="color:red">Could not select database!</span></strong></p><p>' . mysql_error() . "</p>\n";
			return;
		}
		else
		{
			// perform SQL query
			$result = mysql_query($query);
			if (!$result)
			{
				$db_connect_result = '<p><img src="images/red-cross.png" style="margin-right:5px;margin-bottom:-3px;" alt="" /><strong><span style="color:red">Query failed!</span></strong></p><p>' . mysql_error() . "</p>\n";
				return;
			}
			else
			{
				$db_connect_result = '<p><img src="images/tick-clean.png" style="margin-right:5px;margin-bottom:-3px;" alt="" /><strong><span style="color:green">Successful</span></strong></p>';
			}
		}
	}

	// create html from results
	// create data table
	$db_data .= "<table>\n";
   	$db_data .= " <tr>\n  ";
	$line_count = 0;
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		// only print array keys (fields) on first iteration
		if ($line_count == 0)
		{
			$db_data .= " <tr>\n";
			foreach (array_keys($line) as $field_value)
			{
		    	$db_data .= "<th>$field_value</th>";
		 	}
		 	$db_data .= " </tr>\n";
		}
		$db_data .= " <tr>\n";
		foreach ($line as $col_value)
		{
		    $db_data .= "<td>$col_value</td>\n";
		}
		$db_data .= " </tr>\n";
		$line_count++;
	}
 	$db_data .= "</table>\n";
 	
 	$db_data .= "<p>Returned " . mysql_num_rows($result) . " rows for " . mysql_num_fields($result) . " fields.</p>\n";
 	
	// Free resultset
	mysql_free_result($result);

	// Closing connection
	mysql_close($link);
}

?>
