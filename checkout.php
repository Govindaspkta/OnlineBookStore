<?php
session_start();

// Include database connection
@include('admin/config/adminDbConn.php');

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: loginForm.php");
    exit();
}

// Function to sanitize input data
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Variables to control form display and book downloads
$show_form = true;
$show_confirmation = false;
$book_files = []; // To store book file paths for download

if(isset($_POST['order_btn'])) {
    // Get form inputs and sanitize them
    $name = sanitize_input($_POST['name']);
    $number = sanitize_input($_POST['number']);
    $email = sanitize_input($_POST['email']);
    $method = sanitize_input($_POST['method']);
    $country = sanitize_input($_POST['country']);

    // Initialize variables for order details
    $total_price = 0;
    $total_books = 0;
    $product_name = []; // Array to store product names for order details

    // Calculate total price and gather product names
    $cart_query = mysqli_query($con, "SELECT * FROM `cart` WHERE user_id = '{$_SESSION['user_id']}'");
    if(mysqli_num_rows($cart_query) > 0) {
        while($product_item = mysqli_fetch_assoc($cart_query)) {
            $product_name[] = $product_item['name'] .' ('. $product_item['quantity'] .') ';
            $total_price += $product_item['price'] * $product_item['quantity'];
            $total_books += (int)$product_item['quantity'];

            // Get the file path for each book (adjust SQL query based on your database structure)
            $book_query = mysqli_query($con, "SELECT file FROM books WHERE book_id = '{$product_item['book_id']}' LIMIT 1");
            if(mysqli_num_rows($book_query) > 0) {
                $book = mysqli_fetch_assoc($book_query);
                $book_files[] = 'uploads/files/' . $book['file']; // Adjust path as per your file structure
            }
        }
    }

    // Get user ID from session
    $user_id = $_SESSION['user_id'];
    $total_product = implode(', ', $product_name); // Convert array to string for order details

    // Insert order details into database
    $detail_query = mysqli_query($con, "INSERT INTO `orderr` (user_id, name, number, email, method, address, total_books, total_price, files) 
                                        VALUES ('$user_id', '$name', '$number', '$email', '$method', '$country', '$total_books', '$total_price', '$total_product')");

    // Remove items from cart after successful checkout
    if($detail_query) {
        // Delete items from cart table
        $delete_cart_query = mysqli_query($con, "DELETE FROM `cart` WHERE user_id = '$user_id'");
        if($delete_cart_query) {
            $show_form = false;
            $show_confirmation = true;
        } else {
            echo "Failed to remove items from cart.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<script src="https://js.stripe.com/v3/"></script>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="orderconfirm.css">


    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="checkout.css">
</head>
<body>

<div class="container">
    <section class="checkout-form" <?php if($show_form) echo 'style="display: block;"'; else echo 'style="display: none;"'; ?>>
        <h1 class="heading">Complete Your Order</h1>
        <form id="orderForm" action="" method="post">
            <!-- Display order -->
            <div class="display-order">
                <?php
                $select_cart = mysqli_query($con, "SELECT * FROM `cart` WHERE user_id = '{$_SESSION['user_id']}'");
                $grand_total = 0; // Initialize grand total here
                if(mysqli_num_rows($select_cart) > 0) {
                    while($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                        $total_price = $fetch_cart['price'] * $fetch_cart['quantity'];
                        $grand_total += $total_price; // Sum up individual prices
                        echo "<span>{$fetch_cart['name']} ({$fetch_cart['quantity']})</span>";
                    }
                } else {
                    echo "<div class='display-order'><span>Your cart is empty!</span></div>";
                }
                ?>
                <span class="grand-total"> Grand Total: Rs.<?= $grand_total; ?>/- </span>
            </div>
            <!-- Customer details form -->
            <div class="flex">
                <div class="inputBox">
                    <span>Name</span>
                    <input type="text" placeholder="Enter your name" name="name" required>
                </div>
                <div class="inputBox">
                    <span>Number</span>
                    <input type="text" placeholder="Enter your number" name="number" pattern="[0-9]+" title="Number should contain only digits" required>
                </div>
                <div class="inputBox">
                    <span>Email</span>
                    <input type="email" placeholder="Enter your email" name="email" required>
                </div>
                <div class="inputBox">
                    <span>Payment Method</span>
                    <select name="method">
                        <option value="FreeForFirst3days" selected>Online Banking</option>
                        <option value="credit card">Credit Card</option>
                        <option value="paypal">PayPal</option>
                    </select>
                </div>
                <div class="inputBox">
                    <span>Country</span>
                    <input type="text" placeholder="e.g. Nepal" name="country" pattern="[aA-zZ]+" title="Country should contain only alphabets" required>
                </div>
            </div>
            <input type="submit" value="Order Now" name="order_btn" class="btn">
        </form>
    </section>

    <section class="order-confirmation" <?php if($show_confirmation) echo 'style="display: block;"'; else echo 'style="display: none;"'; ?>>
        <?php
        if($show_confirmation) {
            // Display the order confirmation message here
            echo "
            <div class='order-message-container'>
                <div class='message-container'>
                    <h3>Thank you for shopping!</h3>
                    <div class='order-detail'>
                        <span>".$total_product."</span>
                        <span class='total'> Total: Rs.".$total_price."/- </span>
                    </div>
                    <div class='customer-details'>
                        <p>Your name: <span>".$name."</span></p>
                        <p>Your number: <span>".$number."</span></p>
                        <p>Your email: <span>".$email."</span></p>
                        <p>Your address: <span>".$country."</span></p>
                        <p>Your payment mode: <span>".$method."</span></p>
                    </div>
                    <a href='products.php' class='btn'>Continue Shopping</a>
                </div>
            </div>
            ";

            // Automatically download files after confirmation
            echo "<script>";
            foreach ($book_files as $file) {
                if(file_exists($file)) {
                    $file_name = basename($file);
                    echo "var link = document.createElement('a');";
                    echo "link.href = '{$file}';";
                    echo "link.download = '{$file_name}';";
                    echo "document.body.appendChild(link);";
                    echo "link.click();";
                    echo "document.body.removeChild(link);";
                } else {
                    echo "console.error('File {$file} does not exist.');";
                }
            }
            echo "</script>";
        }
        ?>
    </section>
</div>

<!-- Custom JS file link -->
<script src="js/script.js"></script>
</body>
</html>
