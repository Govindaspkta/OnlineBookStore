<?php
session_start();
include("admin/config/adminDbConn.php");

if (isset($_POST['update_quantity_id'], $_POST['update_quantity'])) {
    $cart_id = $_POST['update_quantity_id'];
    $quantity = $_POST['update_quantity'];

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $query = $con->prepare("UPDATE cart SET quantity = ? WHERE cart_id = ? AND user_id = ?");
        $query->bind_param("iii", $quantity, $cart_id, $user_id);
        $query->execute();
    } else {
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['book_id'] == $cart_id) {
                $item['quantity'] = $quantity;
                break;
            }
        }
    }
}

if (isset($_GET['remove'])) {
    $cart_id = $_GET['remove'];

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $query = $con->prepare("DELETE FROM cart WHERE cart_id = ? AND user_id = ?");
        $query->bind_param("ii", $cart_id, $user_id);
        $query->execute();
    } else {
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['book_id'] == $cart_id) {
                unset($_SESSION['cart'][$key]);
                break;
            }
        }
    }
}

if (isset($_GET['delete_all'])) {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $query = $con->prepare("DELETE FROM cart WHERE user_id = ?");
        $query->bind_param("i", $user_id);
        $query->execute();
    } else {
        unset($_SESSION['cart']);
    }
}

// Fetch cart items
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$grand_total = 0;

if ($user_id) {
    // Fetch items from the database cart
    $select_cart = $con->prepare("
        SELECT cart.*, books.title AS book_name, books.cover AS book_cover, books.price AS book_price 
        FROM cart 
        JOIN books ON cart.book_id = books.book_id 
        WHERE cart.user_id = ?
    ");
    $select_cart->bind_param("i", $user_id);
    $select_cart->execute();
    $result_cart = $select_cart->get_result();
} else {
    // Fetch items from the session cart
    $result_cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="cart.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <section class="shopping-cart">
            <h1 class="heading">Shopping Cart</h1>
            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($user_id && $result_cart->num_rows > 0): ?>
                        <?php while ($fetch_cart = $result_cart->fetch_assoc()): ?>
                            <?php
                            $subtotal = $fetch_cart['book_price'] * $fetch_cart['quantity'];
                            $grand_total += $subtotal;
                            ?>
                            <tr>
                                <td><img src="uploads/cover/<?php echo htmlspecialchars($fetch_cart['book_cover']); ?>" height="100" alt=""></td>
                                <td><?php echo htmlspecialchars($fetch_cart['book_name']); ?></td>
                                <td>Rs.<?php echo number_format($fetch_cart['book_price']); ?>/-</td>
                                <td>
                                    <form action="cart.php" method="post" class="update-form">
                                        <input type="hidden" name="update_quantity_id" value="<?php echo $fetch_cart['cart_id']; ?>">
                                        <input type="number" name="update_quantity" min="1" max="9999" value="<?php echo $fetch_cart['quantity']; ?>" onchange="this.form.submit()">
                                    </form>
                                </td>
                                <td>Rs.<?php echo number_format($subtotal); ?>/-</td>
                                <td><a href="cart.php?remove=<?php echo $fetch_cart['cart_id']; ?>" onclick="return confirm('Remove item from cart?')" class="delete-btn"> <i class="fas fa-trash"></i> remove</a></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php elseif (!$user_id && count($result_cart) > 0): ?>
                        <?php foreach ($result_cart as $fetch_cart): ?>
                            <?php
                            $subtotal = $fetch_cart['price'] * $fetch_cart['quantity'];
                            $grand_total += $subtotal;
                            ?>
                            <tr>
                                <td><img src="uploads/cover/<?php echo htmlspecialchars($fetch_cart['image']); ?>" height="100" alt=""></td>
                                <td><?php echo htmlspecialchars($fetch_cart['name']); ?></td>
                                <td>Rs.<?php echo number_format($fetch_cart['price']); ?>/-</td>
                                <td>
                                    <form action="cart.php" method="post" class="update-form">
                                        <input type="hidden" name="update_quantity_id" value="<?php echo $fetch_cart['book_id']; ?>">
                                        <input type="number" name="update_quantity" min="1" max="9999" value="<?php echo $fetch_cart['quantity']; ?>" onchange="this.form.submit()">
                                    </form>
                                </td>
                                <td>Rs.<?php echo number_format($subtotal); ?>/-</td>
                                <td><a href="cart.php?remove=<?php echo $fetch_cart['book_id']; ?>" onclick="return confirm('Remove item from cart?')" class="delete-btn"> <i class="fas fa-trash"></i> remove</a></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No items in cart</td>
                        </tr>
                    <?php endif; ?>
                    <tr class="table-bottom">
                        <td><a href="products.php" class="option-btn" style="margin-top: 0;">Continue Shopping</a></td>
                        <td colspan="3">Grand Total</td>
                        <td>Rs.<?php echo number_format($grand_total); ?>/-</td>
                        <td><a href="cart.php?delete_all" onclick="return confirm('Are you sure you want to delete all?');" class="delete-btn"> <i class="fas fa-trash"></i> Delete All </a></td>
                    </tr>
                </tbody>
            </table>
            <div class="checkout-btn">
                <a href="<?= isset($_SESSION['user_id']) ? 'checkout.php' : 'loginForm.php'; ?>" class="btn <?= $grand_total > 0 ? '' : 'disabled'; ?>">Proceed to Checkout</a>
            </div>
        </section>
    </div>
    <script src="js/script.js"></script>
    <script>
        $(document).ready(function(){
            $('.update-form input[type="number"]').on('change', function(){
                $(this).closest('form').submit();
            });
        });
    </script>
</body>
</html>
