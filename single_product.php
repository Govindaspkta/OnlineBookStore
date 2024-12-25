<?php
session_start();
include("admin/config/adminDbConn.php");

// Check if a book ID is provided
if (isset($_GET['book_id'])) {
    // Sanitize the input to prevent SQL injection
    $book_id = mysqli_real_escape_string($con, $_GET['book_id']);
    
    // Query to fetch the details of the book based on its ID
    $query = "SELECT b.*, a.name AS author_name FROM books b 
              INNER JOIN author a ON b.author_id = a.author_id 
              WHERE b.book_id = '$book_id'";
    $result = mysqli_query($con, $query);

    // Check if the book exists
    if (mysqli_num_rows($result) > 0) {
        // Fetch book details
        $book = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($book['title']); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .book {
            text-align: center;
        }

        .book img {
            max-width: 80%;
            height: auto;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .info h2 {
            margin-top: 0;
        }

        .info h3 {
            margin-top: 5px;
            color: #666;
        }

        .info p {
            margin-top: 10px;
        }

        .price {
            font-weight: bold;
            color: #007bff;
        }

        .add-to-cart-btn {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .add-to-cart-btn:hover {
            background-color: #0056b3;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#add-to-cart-form').on('submit', function(e){
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: 'add_to_cart.php',
                    data: $(this).serialize(),
                    success: function(response){
                        alert(response);
                        updateCartCount();
                    }
                });
            });

            function updateCartCount() {
                $.ajax({
                    url: 'get_cart_count.php',
                    method: 'GET',
                    success: function(data) {
                        $('#cart-count').text(data);
                    }
                });
            }

            updateCartCount(); // Initial call to set cart count on page load
        });
    </script>
</head>
<body>
    <header>
        <h1><?php echo htmlspecialchars($book['title']); ?></h1>
    </header>
    <main>
        <div class="container">
            <div class="book">
                <img src="uploads/cover/<?php echo htmlspecialchars($book['cover']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?> Cover">
                <div class="info">
                    <h2><?php echo htmlspecialchars($book['title']); ?></h2>
                    <h3>By <?php echo htmlspecialchars($book['author_name']); ?></h3>
                    <p><?php echo nl2br(htmlspecialchars($book['description'])); ?></p>
                    <p class="price">Rs.<?php echo htmlspecialchars($book['price']); ?></p>
                    <!-- Add to Cart button -->
                    <form id="add-to-cart-form">
                        <input type="hidden" name="book_id" value="<?php echo htmlspecialchars($book['book_id']); ?>">
                        <input type="hidden" name="book_name" value="<?php echo htmlspecialchars($book['title']); ?>">
                        <input type="hidden" name="book_cover" value="<?php echo htmlspecialchars($book['cover']); ?>">
                        <input type="hidden" name="book_price" value="<?php echo htmlspecialchars($book['price']); ?>">
                        <?php
                        // Check if the book is already in the cart
                        $is_in_cart = false;
                        if(isset($_SESSION['user_id'])) {
                            $user_id = $_SESSION['user_id'];
                            $check_query = $con->prepare("SELECT * FROM cart WHERE user_id = ? AND book_id = ?");
                            $check_query->bind_param("ii", $user_id, $book_id);
                            $check_query->execute();
                            $check_query->store_result();
                            if($check_query->num_rows > 0) {
                                $is_in_cart = true;
                            }
                            $check_query->close();
                        } else {
                            if(isset($_SESSION['cart'])) {
                                foreach($_SESSION['cart'] as $cart_item) {
                                    if($cart_item['book_id'] == $book_id) {
                                        $is_in_cart = true;
                                        break;
                                    }
                                }
                            }
                        }
                        ?>
                        <?php if($is_in_cart): ?>
                            <button class="add-to-cart-btn" disabled>Already in Cart</button>
                        <?php else: ?>
                            <button type="submit" class="add-to-cart-btn">Add to Cart</button>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>
</html>

<?php
    } else {
        echo "Book not found.";
    }
} else {
    echo "Book ID not provided.";
}
?>
