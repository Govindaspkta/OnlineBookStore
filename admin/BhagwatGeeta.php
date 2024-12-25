
<?php
$con = mysqli_connect("localhost", "root", "Mysql..@11", "project4thsem");

if(isset($_POST['add_to_cart'])){
    // Retrieve product details from the form
    $book_name = $_POST['book_name'];
    $book_cover = $_POST['book_cover'];
    $book_price = $_POST['book_price'];
    $book_quantity = 1; // Assuming default quantity is 1

    // Prepare the SQL statement with a parameterized query
    $stmt = $con->prepare("INSERT INTO cart (name, price, image, quantity) VALUES (?, ?, ?, ?)");

    // Bind the parameters
    $stmt->bind_param("sdsi", $book_name, $book_price, $book_cover, $book_quantity);

    // Execute the statement
    if($stmt->execute()) {
        echo "<script>alert('Product added successfully');</script>";
    } else {
        echo "<script>alert('Failed to add product to cart');</script>";
    }

    // Close the statement
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bhagwat Geeta</title>
    <style>
      
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: antiquewhite;
         
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: white;
        }

        .book {
            text-align: center; /* Align book content in the center */
        }

        .book img {
            max-width: 80%; /* Adjust the size of the book cover */
            height: auto;
            border-radius: 5px;
            margin-bottom: 10px; /* Add some bottom margin */
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
</head>
<body>
    <header>
        <h1>Bhagawat Geeta</h1>
    </header>
    <main>
        <div class="container">
            <?php
            // Include Function.php to access its functions
            require_once('Function.php');

            // Establish database connection
            $con = mysqli_connect("localhost", "root", "Mysql..@11", "project4thsem");
            if (!$con) {
                header("location:./errors/db.php");
                exit;
            }

            // Fetch the single book named "Devi Bhagawat Mahapuran" from the category "Literature"
            $select_books = mysqli_query($con,"SELECT books.*, author.name AS author_name FROM books INNER JOIN author ON books.author_id = author.author_id INNER JOIN category ON books.category_id = category.category_id WHERE category.category_name = 'Literature' AND books.title = 'Bhagavad Geeta'");
            if(mysqli_num_rows($select_books) > 0){
                while($fetch_books = mysqli_fetch_assoc($select_books)){
            ?>
            <!-- Display the book -->
            <div class="book">
                <img src="../uploads/cover/<?php echo $fetch_books['cover']; ?>" alt="<?php echo $fetch_books['title']; ?> Cover">
                <div class="info">
                    <h2><?php echo $fetch_books['title']; ?></h2>
                    <h3>By <?php echo $fetch_books['author_name']; ?></h3>
                    <p><?php echo $fetch_books['description']; ?></p>
                    <p class="price">Rs.<?php echo $fetch_books['price']; ?></p>
                    <!-- Add to Cart button -->
                    <form action="" method="post">
                        <input type="hidden" name="book_name" value="<?php echo $fetch_books['title']; ?>">
                        <input type="hidden" name="book_cover" value="<?php echo $fetch_books['cover']; ?>">
                        <input type="hidden" name="book_price" value="<?php echo $fetch_books['price']; ?>">
                        <button type="submit" class="add-to-cart-btn" name="add_to_cart">Add to Cart</button>
                    </form>
                </div>
            </div>
            <?php
                }
            } else {
                echo "No book found with the name 'Devi Bhagawat Mahapuran' in the category 'Literature'.";
            }
            ?>
        </div>
    </main>
</body>
</html>
<script>
   
    // Function to redirect to cart.php
    function redirectToCart() {
        window.location.href = "cart.php";
    }
</script>
