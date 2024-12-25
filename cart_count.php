<?php
session_start();
include("db.php");

function getCartItemCount($user_id, $con) {
    $count_query = "SELECT SUM(quantity) AS total_items FROM cart WHERE user_id = '$user_id'";
    $count_result = mysqli_query($con, $count_query);
    $row = mysqli_fetch_assoc($count_result);
    return $row['total_items'] ? $row['total_items'] : 0;
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $cart_item_count = getCartItemCount($user_id, $con);
} else {
    $cart_item_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
}

echo $cart_item_count;
?>
