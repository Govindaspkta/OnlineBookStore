<?php
include('Function.php');

$con = mysqli_connect("localhost", "root", "Mysql..@11", "project4thsem");
if (!$con) {
    header("location:./errors/db.php");
    exit;
}

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = null;
}

// Add to cart logic
if (isset($_POST['add_to_cart'])) {
    $book_name = $_POST['book_name'];
    $book_cover = $_POST['book_cover'];
    $book_price = $_POST['book_price'];
    $book_quantity = 1; 
    $book_id = $_POST['book_id']; 

    if ($user_id) {
        $stmt = $con->prepare("SELECT quantity FROM cart WHERE user_id = ? AND book_id = ?");
        $stmt->bind_param("ii", $user_id, $book_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "<script>alert('Product already added to the cart');</script>";
        } else {
            $insert_stmt = $con->prepare("INSERT INTO cart (cart_id, user_id, name, price, image, quantity, book_id) VALUES (NULL, ?, ?, ?, ?, ?, ?)");
            $insert_stmt->bind_param("isdsii", $user_id, $book_name, $book_price, $book_cover, $book_quantity, $book_id);

            if ($insert_stmt->execute()) {
                echo "<script>alert('Product added successfully');</script>";
            } else {
                echo "<script>alert('Failed to add product to cart');</script>";
            }
            $insert_stmt->close();
        }
        $stmt->close();
    } else {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $cart_item = [
            'book_id' => $book_id,
            'name' => $book_name,
            'price' => $book_price,
            'image' => $book_cover,
            'quantity' => $book_quantity
        ];

        if (isset($_SESSION['cart'][$book_id])) {
            echo "<script>alert('Product already added to the cart');</script>";
        } else {
            $_SESSION['cart'][$book_id] = $cart_item;
            echo "<script>alert('Product added successfully');</script>";
        }
    }
}

// Merge session cart with user cart after login
function merge_carts($user_id, $con) {
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $book_id => $item) {
            $stmt = $con->prepare("SELECT quantity FROM cart WHERE user_id = ? AND book_id = ?");
            $stmt->bind_param("ii", $user_id, $book_id);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows == 0) {
                $insert_stmt = $con->prepare("INSERT INTO cart (cart_id, user_id, name, price, image, quantity, book_id) VALUES (NULL, ?, ?, ?, ?, ?, ?)");
                $insert_stmt->bind_param("isdsii", $user_id, $item['name'], $item['price'], $item['image'], $item['quantity'], $book_id);
                $insert_stmt->execute();
                $insert_stmt->close();
            }
            $stmt->close();
        }
        unset($_SESSION['cart']);
    }
}

if ($user_id) {
    merge_carts($user_id, $con);
}

?>

<html>
<head>
    <link rel="stylesheet"  href="selfhelp.css">
</head>
<body>
<section class="products">
    <h1 class="heading">Programming</h1>
    <div class="box-container">
        <?php
        $select_books = mysqli_query($con, "SELECT books.* FROM books INNER JOIN category ON books.category_id = category.category_id WHERE category.category_name = 'Programming'");
        if (mysqli_num_rows($select_books) > 0) {
            while ($fetch_books = mysqli_fetch_assoc($select_books)) {
                ?>
                <form action="" method="post">
                    <div class="box">
                        <img src="../uploads/cover/<?php echo $fetch_books['cover']; ?>" alt="">
                        <h3><?php echo $fetch_books['title']; ?></h3>
                        <div class="price"><?php echo $fetch_books['price']; ?></div>
                        <input type="hidden" name="book_name" value="<?php echo $fetch_books['title']; ?>">
                        <input type="hidden" name="book_cover" value="<?php echo $fetch_books['cover']; ?>">
                        <input type="hidden" name="book_file" value="<?php echo $fetch_books['file']; ?>">
                        <input type="hidden" name="book_price" value="<?php echo $fetch_books['price']; ?>">
                        <input type="hidden" name="book_id" value="<?php echo $fetch_books['book_id']; ?>">
                        <input type="submit" class="btn" value="Add to Cart" name="add_to_cart">
                        <!-- <button type="button" class="btn download-btn" data-file="<?php echo $fetch_books['file']; ?>">Download</button> -->
                    </div>
                </form>
                <?php
            }
        }
        ?>
    </div>
</section>
</body>
</html>
<script>
    function redirectToCart() {
        window.location.href = "cart.php";
    }

    function redirectToDownload(bookFile) {
        window.location.href = "download.php?book_file=" + bookFile;
    }

    document.querySelectorAll('.download-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            var bookFile = this.getAttribute('data-file');
            redirectToDownload(bookFile);
        });
    });
</script>
