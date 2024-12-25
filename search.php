<?php
// Include database connection
include("db.php");

// Fetch search query from GET parameter
$search_query = isset($_GET['query']) ? $_GET['query'] : '';

// Query to search for books by title, author name, price, category, and cover image
$search_sql = "SELECT b.book_id, b.title, 
                      COALESCE(GROUP_CONCAT(DISTINCT a1.name SEPARATOR ', '), a2.name) AS author_names, 
                      b.price, c.category_name, b.cover 
               FROM books b 
               INNER JOIN category c ON b.category_id = c.category_id 
               LEFT JOIN books_authors ba ON b.book_id = ba.book_id
               LEFT JOIN author a1 ON ba.author_id = a1.author_id
               LEFT JOIN author a2 ON b.author_id = a2.author_id
               WHERE b.title LIKE '%$search_query%' 
               OR a1.name LIKE '%$search_query%' 
               OR a2.name LIKE '%$search_query%' 
               OR b.price LIKE '%$search_query%' 
               OR c.category_name LIKE '%$search_query%'
               GROUP BY b.book_id";

// Perform the search query
$search_result = mysqli_query($con, $search_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            margin-top: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .book-cover {
            max-width: 100px;
            max-height: 150px;
            object-fit: cover;
        }
        .title-link {
            text-decoration: none;
            color: #333;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Search Results</h2>
    
    <?php if(mysqli_num_rows($search_result) > 0): ?>
    <table>
        <tr>
            <th>Book Cover</th>
            <th>Book Name</th>
            <th>Author Name(s)</th>
            <th>Price</th>
            <th>Category</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($search_result)): ?>
        <tr>
            <td><img src="uploads/cover/<?php echo $row['cover']; ?>" height="100" alt=""></td>
            <td><a href="single_product.php?book_id=<?php echo $row['book_id']; ?>" class="title-link"><?php echo isset($row['title']) ? $row['title'] : 'N/A'; ?></a></td>
            <td><?php echo isset($row['author_names']) ? $row['author_names'] : 'N/A'; ?></td>
            <td><?php echo isset($row['price']) ? $row['price'] : 'N/A'; ?></td>
            <td><?php echo isset($row['category_name']) ? $row['category_name'] : 'N/A'; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <?php else: ?>
        <p>No results found.</p>
    <?php endif; ?>
</div>

</body>
</html>
