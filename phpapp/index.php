<?php
// Connecting, selecting database
$db_host=getenv("DB_MYSQL_SERVICE_HOST");
$db_port=getenv("DB_MYSQL_SERVICE_PORT");
$db_user=getenv("DB_MYSQL_USER");
$db_passwd=getenv("DB_MYSQL_PASSWD");
$pod_name=getenv('HOSTNAME');

$link = mysql_connect($db_host . ':' . $db_port, $db_user, $db_passwd)
    or die('Could not connect: ' . mysql_error());

$db_selected = mysql_select_db('my_database');

if (!$db_selected) {
        echo "Creating db \n";
        $sql = 'CREATE DATABASE my_database';

        if (mysql_query($sql)) {
          echo "Database my_database created successfully\n";
        } else {
          echo 'Error creating database: ' . mysql_error() . "\n";
        }
}

$db_selected = mysql_select_db('my_database') or die('failed to select db');

// Performing SQL query
$query = "SELECT 1 FROM my_table";
$result = mysql_query($query);
if (empty($result)) {
        echo "Attepmting to create table\n";
        $tab_query = "CREATE TABLE my_table(id int(11) AUTO_INCREMENT,
                                            rand_entry int,
                                            pod_name varchar(30), PRIMARY KEY(id))";
        if (mysql_query($tab_query)) {
                echo "Created table\n";
        } else {
                echo "Failed to create table " . mysql_error() . "\n";
        }
} else {
        mysql_free_result($result);
}

$randint = rand(10000, 99999);
$insertq = "INSERT INTO my_table (rand_entry, pod_name) values ($randint, '$pod_name')";
mysql_query($insertq) or die("Failed to insert record" . mysql_error());

$query = "SELECT * FROM my_table ORDER BY id DESC";
$result = mysql_query($query);

// Printing results in HTML
echo "<script src=\"sorttable.js\"></script>";
echo "<br/><b>Pod $pod_name</b>";
echo "<table border=1 class=\"sortable\">\n";
echo "<tr><th>Id</th><th>RandInt</th><th>Pod</th></tr>";while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
    echo "\t<tr>\n";
    foreach ($line as $col_value) {
        echo "\t\t<td>$col_value</td>\n";
    }
    echo "\t</tr>\n";
}
echo "</table>\n";

// Free resultset
mysql_free_result($result);

// Closing connection
mysql_close($link);
?>
