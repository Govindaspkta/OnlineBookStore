<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multidimensional Associative Array Elements</title>
    <style>
        table {
            border-collapse: collapse;
            width: 50%;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<?php
// Define a multidimensional array containing three associative arrays as its elements
$multidimensional_array = array(
    array("name" => "Aashish", "age" => 22, "city" => " Ktm"),
    array("name" => "Govinda", "age" => 23, "city" => " Ktm"),
    array("name" => "Govinda", "age" => 23, "city" => "New York")

);
?>

<h2>Multidimensional Associative Array Elements</h2>
<table>
    <tr>
        <th>Name</th>
        <th>Age</th>
        <th>City</th>
    </tr>[ -]
    <?php             
    // Loop through the multidimensional array and display its members in a table
    foreach ($multidimensional_array as $row) {
        echo "<tr>";
        echo "<td>" . $row['name'] . "</td>";
        echo "<td>" . $row['age'] . "</td>";
        echo "<td>" . $row['city'] . "</td>";
        echo "</tr>"; 
    }
    ?>
</table>

</body>
</html>
