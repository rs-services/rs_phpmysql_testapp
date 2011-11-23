<?php

function do_mysqli($host, $user, $password, $schema)
{
	global $db_connect_result, $db_select_table_result, $db_data;
	$db_data = false;
	
	$mysqli = new mysqli($host, $user, $password, $schema);
	
	/* check connection */
	if ($mysqli->connect_errno) {
		$db_connect_result = '<img src="images/red-cross.png" style="margin-right:5px;margin-bottom:-3px; alt="" /><strong><span style="color:red">Connect failed</span></strong>: '	. $mysqli->connect_error;
	}
	else
	{
		$db_connect_result = '<img src="images/tick-clean.png" style="margin-right:5px;margin-bottom:-3px; alt="" /><strong><span style="color:green">Successful</span></strong>';
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

function do_mysql($host, $user, $password)
{
	global $db_connect_result, $db_select_table_result, $db_data;
	$db_data = false;

	$query = "SELECT * FROM `phptest`;";

	// connected to db server
	$link = mysql_connect($host, $user, $password)
		or $db_connect_result .= '<strong><span style="color:red">Connect failed</span></strong>: '	. mysql_error();

	// select database
	mysql_select_db('phptest')
		or $db_connect_result .= '<strong><span style="color:red">Could not select database.</span></strong>: ';

	// performi SQL query
	$result = mysql_query($query)
		or $db_connect_result .= 'Query failed: ' . mysql_error();
		
	// create html from results
	$line_count = 0;
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC))
	{
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
		
	// Free resultset
	mysql_free_result($result);

	// Closing connection
	mysql_close($link);
}

function do_pdo($driver, $host, $db, $charset, $user, $password)
{
	global $db_connect_result, $db_select_table_result, $db_data;
	$db_data = false;
	
	$db = new PDO("$driver:host=$host;dbname=$db;charset=$charset", $user, $password);
	$results = $db->query("SELECT * FROM `phptest`;");
	
}

?>
