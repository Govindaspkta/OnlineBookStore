<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Home</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .adminhome {
            text-align: center;
            margin-top: 20px;
            color: #343a40;
            font-size: 36px;
        }

        .box-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 20px;
        }

        .box {
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            text-align: center;
            width: 300px; /* Increased width */
            height: 200px; /* Increased height */
            transition: transform 0.2s;
        }

        .box:hover {
            transform: scale(1.05);
        }

        .box h2 {
            font-size: 28px;
            color: #007bff;
        }

        .box p {
            font-size: 18px;
            color: #6c757d;
        }
    </style>
</head>

<body>

    <h1 class="adminhome">Admin Home</h1>

    <div class="container box-container">
     
        <?php
    $con = mysqli_connect("localhost", "root", "Mysql..@11", "project4thsem") or die("Connection failed: " . mysqli_connect_error());
    ?>
           

        <div class="box">
            <?php
            $select_orders = mysqli_query($con, "SELECT COUNT(*) as total_orders FROM `orderr`") or die('query failed');
            $numb_of_orders = mysqli_fetch_assoc($select_orders)['total_orders'];
            ?>
            <h2><?php echo $numb_of_orders; ?></h2>
            <p>Orders Placed</p>
        </div>

   

        <div class="box">
            <?php
            $select_users = mysqli_query($con, "SELECT COUNT(*) as total_users FROM `uuser` WHERE user_type='user'") or die('query failed');
            $numb_of_users = mysqli_fetch_assoc($select_users)['total_users'];
            ?>
            <h2><?php echo $numb_of_users; ?></h2>
            <p>Normal Users</p>
        </div>

        <div class="box">
            <?php
            $select_admin = mysqli_query($con, "SELECT COUNT(*) as total_admin FROM `uuser` WHERE user_type='admin'") or die('query failed');
            $numb_of_admin = mysqli_fetch_assoc($select_admin)['total_admin'];
            ?>
            <h2><?php echo $numb_of_admin; ?></h2>
            <p>Admin Users</p>
        </div>

        <div class="box">
            <?php
            $select_authors = mysqli_query($con, "SELECT COUNT(*) as total_authors FROM `author`") or die('query failed');
            $numb_of_authors = mysqli_fetch_assoc($select_authors)['total_authors'];
            ?>
            <h2><?php echo $numb_of_authors; ?></h2>
            <p>Total Authors</p>
        </div>

        <div class="box">
            <?php
            $select_categories = mysqli_query($con, "SELECT COUNT(*) as total_categories FROM `category`") or die('query failed');
            $numb_of_categories = mysqli_fetch_assoc($select_categories)['total_categories'];
            ?>
            <h2><?php echo $numb_of_categories; ?></h2>
            <p>Total Categories</p>
        </div>
    </div>

</body>

</html>
