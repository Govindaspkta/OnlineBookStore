<?php
session_start();
include("admin/config/adminDbConn.php");

// Function to get cart item count for a user
function getCartItemCount($user_id, $con) {
    $count_query = "SELECT SUM(quantity) AS total_items FROM cart WHERE user_id = ?";
    $stmt = $con->prepare($count_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row['total_items'] ? $row['total_items'] : 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ["status" => "", "cart_count" => 0];
    $book_id = $_POST['book_id'];
    $book_name = $_POST['book_name'];
    $book_price = $_POST['book_price'];
    $book_cover = $_POST['book_cover'];
    $quantity = 1; // Default quantity to 1

    // Check if user is logged in
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        // Check if product already in cart
        $check_query = "SELECT * FROM cart WHERE user_id = ? AND book_id = ?";
        $stmt = $con->prepare($check_query);
        $stmt->bind_param("ii", $user_id, $book_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Product already in cart
            $response["status"] = "Product already added to the cart";
        } else {
            // Insert product into cart
            $insert_query = "INSERT INTO cart (user_id, book_id, name, price, quantity, image) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $con->prepare($insert_query);
            $stmt->bind_param("iisdss", $user_id, $book_id, $book_name, $book_price, $quantity, $book_cover);
            if ($stmt->execute()) {
                $response["status"] = "Product added successfully";
                $response["cart_count"] = getCartItemCount($user_id, $con);
            } else {
                $response["status"] = "Failed to add product to cart";
            }
        }
        $stmt->close();
    } else {
        // User not logged in, handle session cart
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Check if product already in session cart
        $is_in_cart = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['book_id'] == $book_id) {
                $is_in_cart = true;
                break;
            }
        }

        if ($is_in_cart) {
            // Product already in session cart
            $response["status"] = "Product already added to the cart";
        } else {
            // Add product to session cart
            $_SESSION['cart'][] = [
                'book_id' => $book_id,
                'book_name' => $book_name,
                'book_cover' => $book_cover,
                'book_price' => $book_price,
                'quantity' => $quantity,
            ];
            $response["status"] = "Product added successfully";
            $response["cart_count"] = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0;
        }
    }

    // Return JSON response for AJAX requests
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        echo json_encode($response);
    } else {
        // Redirect to products.php if not an AJAX request
        header('Location: products.php');
    }
}
?>
