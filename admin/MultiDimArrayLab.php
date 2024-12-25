<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multidimensional Array Elements</title>
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
// Define a multidimensional array
$employees = array(
    array("Govinda", "Developer"),
    array("Aashish", "Student")
    
);
?>

<h2>Employee Information</h2>
<table>
    <tr>
        <th>First Name</th>
        <th>Profession</th>
    </tr>
    <?php
    // Loop through the multidimensional array and display its elements in a table
    foreach ($employees as $employee) {
        echo "<tr>";
        foreach ($employee as $info) {
            echo "<td>$info</td>";
        }
        echo "</tr>";
    }
    ?>
</table>

</body>
</html>
