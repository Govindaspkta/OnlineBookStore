<?php
include("config/adminDbconn.php");

function get_authors($con) {
    $sql = "SELECT * FROM author";
    $result = mysqli_query($con, $sql);
    
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $author = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $author[] = $row;
            }
        } else {
            $author = "No authors found.";
        }
    } else {
        $author = "Error executing query: " . mysqli_error($con);
    }
    return $author;
}

function get_category($con) {
    $sqll = "SELECT * FROM category";
    $result = mysqli_query($con, $sqll);
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $category = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $category[] = $row;
            }
        } else {
            $category = "No categories found.";
        }
    } else {
        $category = "Error executing query: " . mysqli_error($con);
    }
    return $category;
}
?>
