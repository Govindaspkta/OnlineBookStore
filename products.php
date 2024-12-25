
<?php
// session_start();
include("nav.php");
include("admin/config/adminDbConn.php");

if(isset($_SESSION['user_id'])) {
    $logged_in = true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books</title>
    <link rel="stylesheet" href="productsCss.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
            padding-top: 150px;
        }
        .books-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 20px;
        }
        .h1 {
            text-align: center;
        }
        .book {
            border: 1px solid whitesmoke;
            border-radius: 5px;
            width: calc(25% - 20px);
            margin-bottom: 20px;
            height: auto;
            display: flex;
            flex-direction: column;
        }
        .book img.book-cover {
            width: 100%;
            height: 350px;
            object-fit: cover;
        }
        .book-details {
            padding: 10px;
        }
        .book h2 {
            font-size: 1.2em;
            margin: 10px 0;
        }
        .book h4 {
            margin: 5px 0;
        }
        .add-to-cart-btn, .download-btn {
            padding: 10px 20px;
            margin-top: auto;
            background-color: royalblue;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            color: white;
            align-self: center;
        }
        .add-to-cart-btn:hover, .download-btn:hover {
            background-color: grey;
        }
        .add-to-cart-btn {
            margin-bottom: 10px; /* Adding margin between the buttons */
        }
        .cart-status {
            text-align: center;
            padding: 10px;
            color: red;
        }
    </style>
</head>
<body>
<div class="container" id="contentContainer">
    <h1>Available Books</h1>
    <div class="books-container">
        <?php
        $query = "SELECT b.book_id, b.title, b.price, b.cover, c.category_name, 
                         COALESCE(
                             GROUP_CONCAT(DISTINCT a1.name SEPARATOR ', '), 
                             a2.name
                         ) AS author_names
                  FROM books b
                  LEFT JOIN books_authors ba ON b.book_id = ba.book_id
                  LEFT JOIN author a1 ON ba.author_id = a1.author_id
                  LEFT JOIN author a2 ON b.author_id = a2.author_id
                  INNER JOIN category c ON b.category_id = c.category_id
                  GROUP BY b.book_id";
        $result = mysqli_query($con, $query);

        if (mysqli_num_rows($result) > 0) {
            $cart_items = [];
            if (isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id'];
                $cart_query = "SELECT book_id FROM cart WHERE user_id = '$user_id'";
                $cart_result = mysqli_query($con, $cart_query);
                while ($cart_row = mysqli_fetch_assoc($cart_result)) {
                    $cart_items[] = $cart_row['book_id'];
                }
            } else if (isset($_SESSION['cart'])) {
                $cart_items = array_column($_SESSION['cart'], 'book_id');
            }

            while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="book">
                    <img src="uploads/cover/<?php echo $row['cover']; ?>" alt="Cover" class="book-cover">
                    <div class="book-details">
                        <h2><?php echo $row['title']; ?></h2>
                        <h4>Author(s): <?php echo $row['author_names'] ? $row['author_names'] : 'N/A'; ?></h4>
                        <h4>Category: <?php echo $row['category_name']; ?></h4>
                        <h4>Price: Rs.<?php echo $row['price']; ?></h4>
                    </div>
                    <?php
                    if (in_array($row['book_id'], $cart_items)) {
                        echo "<p class='cart-status'>Already in Cart</p>";
                    } else {
                        ?>
                        <form class="add-to-cart-form" method="post">
                            <input type="hidden" name="book_id" value="<?php echo $row['book_id']; ?>">
                            <input type="hidden" name="book_name" value="<?php echo $row['title']; ?>">
                            <input type="hidden" name="book_cover" value="<?php echo $row['cover']; ?>">
                            <input type="hidden" name="book_price" value="<?php echo $row['price']; ?>">
                            <button type="submit" class="add-to-cart-btn">Add to Cart</button>
                        </form>
                        <?php
                    }
                    ?>
                    <!-- <form class="download-form" method="post" action="checkout.php">
                        <input type="hidden" name="book_id" value="<?php echo $row['book_id']; ?>">
                        <button type="submit" class="download-btn">Download</button>
                    </form> -->
                </div>
                <?php
            }
        } else {
            echo "No books found.";
        }
        ?>
    </div>
</div>
<script>
    function updateCartCount() {
        $.ajax({
            url: 'cart_count.php',
            method: 'GET',
            success: function(data) {
                $('#cart-count').text(data);
            }
        });
    }

    $(document).ready(function() {
        $('.add-to-cart-form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            $.ajax({
                url: 'add_to_cart.php',
                method: 'POST',
                data: form.serialize(),
                success: function(response) {
                    console.log("AJAX success response: " + response); // Log response for debugging
                    var res = JSON.parse(response);
                    if (res.status === 'Product added successfully') {
                        form.find('.add-to-cart-btn').prop('disabled', true).text('Already in Cart');
                        form.replaceWith("<p class='cart-status'>Already in Cart</p>");
                    }
                    alert(res.status);
                    updateCartCount();
                },
                error: function(xhr, status, error) {
                    console.error("AJAX error: " + error);
                    console.log(xhr.responseText); // Log error response for debugging
                }
            });
        });
    });
</script>
</body>
</html>
<?php
} else {
    header('Location: loginform.php');
    exit();
}

// add_to_cart.php code within the same file for demonstration purposes
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book_id'])) {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $book_id = $_POST['book_id'];
        $book_name = $_POST['book_name'];
        $book_cover = $_POST['book_cover'];
        $book_price = $_POST['book_price'];

        // Check if the book is already in the cart
        $query = "SELECT * FROM cart WHERE user_id = '$user_id' AND book_id = '$book_id'";
        $result = mysqli_query($con, $query);
        
        if (mysqli_num_rows($result) > 0) {
            $response = array('status' => 'Book is already in the cart');
        } else {
            // Add the book to the cart
            $query = "INSERT INTO cart (user_id, book_id, book_name, book_cover, book_price) VALUES ('$user_id', '$book_id', '$book_name', '$book_cover', '$book_price')";
            if (mysqli_query($con, $query)) {
                $response = array('status' => 'Product added successfully');
            } else {
                $response = array('status' => 'Error adding product to cart');
                error_log("Error adding product to cart: " . mysqli_error($con)); // Log error for debugging
            }
        }
    } else {
        $response = array('status' => 'User not logged in');
    }

    echo json_encode($response);
    exit();
}

// cart_count.php functionality within the same file for demonstration purposes
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['cart_count'])) {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $query = "SELECT COUNT(*) AS count FROM cart WHERE user_id = '$user_id'";
        $result = mysqli_query($con, $query);
        $row = mysqli_fetch_assoc($result);
        echo $row['count'];
    } else if (isset

    ($_SESSION['cart'])) {
        echo count($_SESSION['cart']);
    } else {
        echo 0;
    }
    exit();
}

// checkout.php functionality for processing the download after filling out the form
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book_id'])) {
    $book_id = $_POST['book_id'];
    $user_id = $_SESSION['user_id'];
    
    // Assuming the checkout form is filled and the payment is processed
    // Now, fetch the file path from the database
    $query = "SELECT file_path FROM books WHERE book_id = '$book_id'";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $file_path = $row['file_path'];

        if (file_exists($file_path)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file_path).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file_path));
            readfile($file_path);
            exit();
        } else {
            echo "File not found.";
        }
    } else {
        echo "Book not found.";
    }
}
?>
