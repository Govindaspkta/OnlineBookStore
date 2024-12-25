<?php
session_start();
include("db.php");

// Function to get the number of items in the cart for the logged-in user
function getCartItemCount($user_id, $con) {
    $count_query = "SELECT SUM(quantity) AS total_items FROM cart WHERE user_id = '$user_id'";
    $count_result = mysqli_query($con, $count_query);
    $row = mysqli_fetch_assoc($count_result);
    return $row['total_items'] ? $row['total_items'] : 0;
}

// Check if the user is logged in
if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $cart_item_count = getCartItemCount($user_id, $con);
    $logged_in = true;
} else {
    $cart_item_count = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0;
    $logged_in = false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nav</title>
    <link rel="stylesheet" href="nav.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="header">
        <div class="top">
            <div class="img">
                <img src="BookC.png" alt="logo not loaded" width="100px" height="100%">
            </div>
            <div class="navigation">
                <div class="nav">
                    <ul>
                        <li class="dropdown">
                            <a class="category-btn" href="#">Category</a>
                            <ul class="dropdown-content">
                            <?php
                            $db_host = 'localhost';
                            $db_username = 'root';
                            $db_password = 'Mysql..@11';
                            $db_name = 'project4thsem';

                            $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            }

                            $sql = "SELECT category_id, category_name FROM category";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                echo '<div class="dropdown">';
                                echo '<div class="dropdown-content">';
                                
                                while ($row = $result->fetch_assoc()) {
                                    echo '<a href="admin/' . strtolower($row["category_name"]) . '.php">' . $row["category_name"] . '</a>';
                                }
                                
                                echo '</div>';
                                echo '</div>';
                            } else {
                                echo "0 results";
                            }

                            $conn->close();
                            ?>
                            </ul>
                        </li>
                        <li><a href="customeSupport.php">Customer Support</a></li>
                        <li><a href="aboutUs.php">About us</a></li>
                        <li>
                        <form action="search.php" method="get" onsubmit="return false;">
    <input type="text" name="query" placeholder="Search" class="search-input" id="searchQuery">
</form>

                        </li>
                    </ul>
                </div>
            </div>

            <div class="cart-item">
                <a href="cart.php" class="btn btn-outline-success">
                    <i class="fas fa-shopping-cart"></i>
                    <span id="cart-count"><?php echo $cart_item_count; ?></span>
                </a>
            </div>

            <div class="login cart-item">
                <?php if ($logged_in): ?>
                    <a href="user_logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i>Logout</a>
                <?php else: ?>
                    <a href="loginform.php"> Login/Register</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
<script>
    document.getElementById('searchQuery').addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            window.location.href = 'search.php?query=' + encodeURIComponent(this.value);
        }
    });
</script>