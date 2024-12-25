<?php
session_start();
include('includes/header.php');
include('includes/topbar.php');
include('includes/sidebar.php');
include('config/adminDbConn.php');

// Fetch authors and categories
$authorQuery = "SELECT * FROM author";
$categoryQuery = "SELECT * FROM category";

$authorResult = mysqli_query($con, $authorQuery);
$categoryResult = mysqli_query($con, $categoryQuery);

if (!$authorResult || !$categoryResult) {
    die("Failed to fetch data from database.");
}

$authors = mysqli_fetch_all($authorResult, MYSQLI_ASSOC);
$categories = mysqli_fetch_all($categoryResult, MYSQLI_ASSOC);

// Fetch book details if book_id is set
if (isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];
    $query = "SELECT books.*, GROUP_CONCAT(author.author_id SEPARATOR ', ') AS author_ids, GROUP_CONCAT(author.name SEPARATOR ', ') AS author_names
              FROM books 
              LEFT JOIN books_authors ON books.book_id = books_authors.book_id
              LEFT JOIN author ON books_authors.author_id = author.author_id
              WHERE books.book_id='$book_id' 
              GROUP BY books.book_id";
    $result = mysqli_query($con, $query);

    if (!$result) {
        die("Failed to fetch book data.");
    }

    $row = mysqli_fetch_assoc($result);

    // Handle case where no book found with given book_id
    if (!$row) {
        echo "No book found with ID: " . $book_id;
        exit; // Stop further execution
    }

    // Split author_ids and author_names into arrays for multiple authors
    $row['author_ids'] = explode(', ', $row['author_ids']);
    $row['author_names'] = explode(', ', $row['author_names']);
} else {
    echo "No book ID specified.";
    exit; // Stop further execution
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <!-- Include any necessary CSS stylesheets here -->
    <link rel="stylesheet" href="path/to/your/bootstrap.css">
    <style>
        /* Add your custom styles here */
        .form-group {
            position: relative;
        }
        .file-name {
            position: absolute;
            top: calc(100% + 5px);
            left: 0;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Edit Book</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <?php
                            if (isset($_SESSION['errors'])) {
                                echo '<div class="alert alert-danger">';
                                foreach ($_SESSION['errors'] as $error) {
                                    echo '<p>' . htmlspecialchars($error) . '</p>';
                                }
                                echo '</div>';
                                unset($_SESSION['errors']);
                            }
                            ?>
                            <form action="adminCode.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                                <input type="hidden" name="book_id" value="<?= htmlspecialchars($row['book_id']) ?>">
                              

                                <div class="form-group">
                                    <label for="title">Book Name</label>
                                    <input type="text" name="title" id="title" class="form-control" placeholder="Book Name" value="<?= htmlspecialchars($row['title']) ?>" required>
                                    <div class="error" id="title-error"></div>
                                </div>

                                <div class="form-group">
                                    <label for="authors">Authors</label>
                                    <select name="authors[]" id="authors" class="form-control" >
                                        <?php foreach ($authors as $author) : ?>
                                            <option value="<?= htmlspecialchars($author['author_id']); ?>" <?= (in_array($author['author_id'], $row['author_ids'])) ? 'selected' : ''; ?>>
                                                <?= htmlspecialchars($author['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="error" id="authors-error"></div>
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea name="description" id="description" class="form-control" placeholder="Description" required><?= htmlspecialchars($row['description']) ?></textarea>
                                    <div class="error" id="description-error"></div>
                                </div>

                                <div class="form-group">
                                    <label for="category_id">Category</label>
                                    <select name="category_id" id="category_id" class="form-control" required>
                                        <?php foreach ($categories as $category) : ?>
                                            <option value="<?= htmlspecialchars($category['category_id']); ?>" <?= ($row['category_id'] == $category['category_id']) ? 'selected' : ''; ?>>
                                                <?= htmlspecialchars($category['category_name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="error" id="category-error"></div>
                                </div>

                                <div class="form-group">
                                    <label for="file">File:</label>
                                    <input type="file" name="file" id="file" class="form-control">
                                    <?php if (!empty($row['file'])): ?>
                                        <p>Current File: <a href="uploads/files/<?= htmlspecialchars($row['file']); ?>" target="_blank"><?= htmlspecialchars($row['file']); ?></a></p>
                                    <?php endif; ?>
                                    <div class="error" id="file-error"></div>
                                </div>

                                <div class="form-group">
                                    <label for="cover">Cover:</label>
                                    <input type="file" name="cover" id="cover" class="form-control">
                                    <?php if (!empty($row['cover'])): ?>
                                        <p>Current Cover:</p>
                                        <img src="uploads/cover/<?= htmlspecialchars($row['cover']); ?>" alt="Cover Image" style="max-width: 100px;">
                                    <?php endif; ?>
                                    <div class="error" id="cover-error"></div>
                                </div>

                                <div class="form-group">
                                    <label for="price">Price</label>
                                    <input type="number" name="price" id="price" class="form-control" placeholder="Price" value="<?= htmlspecialchars($row['price']) ?>" step="0.01" min="0" required>
                                    <div class="error" id="price-error"></div>
                                </div>

                                <div class="modal-footer">
    <button type="button" class="btn btn-secondary" onclick="window.location.href='registeredBook.php';">Close</button>
    <button type="submit" name="updateBook" class="btn btn-primary">Update Book</button>
</div>



                            </form>

                            <script>
                                function validateForm() {
                                    let isValid = true;

                                    // Clear previous error messages
                                    document.querySelectorAll('.error').forEach(function (element) {
                                        element.innerText = '';
                                    });

                                    // Validate title
                                    const title = document.getElementById('title').value;
                                    if (!title) {
                                        document.getElementById('title-error').innerText = 'Book name is required.';
                                        isValid = false;
                                    }

                                    // Validate authors
                                    const authors = document.getElementById('authors').selectedOptions;
                                    if (authors.length === 0) {
                                        document.getElementById('authors-error').innerText = 'At least one author must be selected.';
                                        isValid = false;
                                    }

                                    // Validate description
                                    const description = document.getElementById('description').value;
                                    if (!description) {
                                        document.getElementById('description-error').innerText = 'Description is required.';
                                        isValid = false;
                                    }

                                    // Validate category
                                    const category = document.getElementById('category_id').value;
                                    if (!category) {
                                        document.getElementById('category-error').innerText = 'Category is required.';
                                        isValid = false;
                                    }

                                    // Validate price
                                    const price = document.getElementById('price').value;
                                    if (price <= 0) {
                                        document.getElementById('price-error').innerText = 'Price must be a positive number.';
                                        isValid = false;
                                    }

                                    // Validate file upload
                                    const file = document.getElementById('file').files[0];
                                    if (file) {
                                        const allowedFileTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                                        const maxFileSize = 5 * 1024 * 1024; // 5 MB
                                        if (!allowedFileTypes.includes(file.type)) {
                                            document.getElementById('file-error').innerText = 'Invalid file type. Only PDF and DOC files are allowed.';
                                            isValid = false;
                                        }
                                        if (file.size > maxFileSize) {
                                            document.getElementById('file-error').innerText = 'File size exceeds the 5 MB limit.';
                                            isValid = false;
                                        }
                                    }

                                    // Validate cover upload
                                    const cover = document.getElementById('cover').files[0];
                                    if (cover) {
                                        const allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
                                        const maxImageSize = 2 * 1024 * 1024; // 2 MB
                                        if (!allowedImageTypes.includes(cover.type)) {
                                            document.getElementById('cover-error').innerText = 'Invalid image type. Only JPG, PNG, and GIF files are allowed.';
                                            isValid = false;
                                        }
                                        if (cover.size > maxImageSize) {
                                            document.getElementById('cover-error').innerText = 'Image size exceeds the 2 MB limit.';
                                            isValid = false;
                                        }
                                    }

                                    return isValid;
                                }
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
include('includes/footer.php');
?>
</body>
</html>
