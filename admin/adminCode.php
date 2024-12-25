<?php
session_start();
include('config/adminDbConn.php');
if(isset($_POST['logout'])){
      session_destroy();
      $_session['status']="logged out sucessfully";
      header('location:..ADMIN/adminform.php');
}
//AUTHOR CRUD

if(isset($_POST['addAuthor'])) {
    // Check if name field is set and not empty
    if(isset($_POST['name']) && !empty($_POST['name'])) {
        $name = mysqli_real_escape_string($con, $_POST['name']); // Sanitize input
        $query = "INSERT INTO author (name) VALUES ('$name')";
        $query_run = mysqli_query($con, $query);
        // Set a session variable to display success message
        if($query_run) {
            $_SESSION['status'] = "Author added successfully!";
        } else {
            $_SESSION['status'] = "Failed to add author.";
        }
    } else {
        // If name field is empty, set an error message
        $_SESSION['status'] = "Please enter author name.";
    }
    // Redirect back to the page where the form was submitted
    header('Location: registered.php');
    exit();
}

if(isset($_POST['addMultipleAuthors'])) {
    // Check if author_names field is set and not empty
    if(isset($_POST['author_names']) && !empty($_POST['author_names'])) {
        $authorNames = explode(',', $_POST['author_names']);
        $success = true;
        foreach($authorNames as $authorName) {
            $name = mysqli_real_escape_string($con, trim($authorName)); // Sanitize input
            if(!empty($name)) {
                $query = "INSERT INTO author (name) VALUES ('$name')";
                $query_run = mysqli_query($con, $query);
                if(!$query_run) {
                    $success = false;
                    break; // Break the loop if any insertion fails
                }
            }
        }
        // Set a session variable based on the success status
        if($success) {
            $_SESSION['status'] = "Authors added successfully!";
        } else {
            $_SESSION['status'] = "Failed to add one or more authors.";
        }
    } else {
        // If author_names field is empty, set an error message
        $_SESSION['status'] = "Please enter at least one author name.";
    }
    // Redirect back to the page where the form was submitted
    header('Location: registered.php');
    exit();
}
// Redirect back to the page where the form was submitted
  
?>

    
    <?php
    include('config/adminDbConn.php');
    
    // Update Book Data in books and books_authors Tables
    if (isset($_POST['updateBook'])) {
        $book_id = $_POST['book_id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $category_id = $_POST['category_id'];
        $price = $_POST['price'];
    
        // File handling
        $file = $_FILES['file']['name'];
        $cover = $_FILES['cover']['name'];
    
        // Fetch existing data
        $query = "SELECT file, cover FROM books WHERE book_id=?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "i", $book_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $existingFile, $existingCover);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    
        // If no new file is uploaded, keep the existing file
        if (!empty($file)) {
            $file_tmp = $_FILES['file']['tmp_name'];
            $upload_dir = "../uploads/files/";
            $file_destination = $upload_dir . $file;
            move_uploaded_file($file_tmp, $file_destination);
        } else {
            $file = $existingFile;
        }
    
        // If no new cover is uploaded, keep the existing cover
        if (!empty($cover)) {
            $cover_tmp = $_FILES['cover']['tmp_name'];
            $upload_dir = "../uploads/cover/";
            $cover_destination = $upload_dir . $cover;
            move_uploaded_file($cover_tmp, $cover_destination);
        } else {
            $cover = $existingCover;
        }
    
        // Prepare to update the book
        $updateQuery = "UPDATE books SET title=?, description=?, category_id=?, file=?, cover=?, price=? WHERE book_id=?";
        $stmt = mysqli_prepare($con, $updateQuery);
        mysqli_stmt_bind_param($stmt, "sssdsdi", $title, $description, $category_id, $file, $cover, $price, $book_id);
    
        if (!mysqli_stmt_execute($stmt)) {
            $_SESSION['status'] = 'Failed to update book: ' . mysqli_stmt_error($stmt);
            header("Location: registeredBook.php");
            exit();
        }
    
        // Determine if we have single or multiple authors
        if (!empty($_POST['authors'])) {
            $authors = $_POST['authors'];
    
            if (count($authors) == 1) {
                // If there is only one author, update the books table directly
                $singleAuthorId = $authors[0];
                $updateSingleAuthorQuery = "UPDATE books SET author_id=? WHERE book_id=?";
                $stmt = mysqli_prepare($con, $updateSingleAuthorQuery);
                mysqli_stmt_bind_param($stmt, "ii", $singleAuthorId, $book_id);
                mysqli_stmt_execute($stmt);
    
                // Ensure no entries in books_authors for this book
                $deleteQuery = "DELETE FROM books_authors WHERE book_id=?";
                $deleteStmt = mysqli_prepare($con, $deleteQuery);
                mysqli_stmt_bind_param($deleteStmt, "i", $book_id);
                mysqli_stmt_execute($deleteStmt);
            } else {
                // If there are multiple authors, update the books_authors table
                $deleteQuery = "DELETE FROM books_authors WHERE book_id=?";
                $deleteStmt = mysqli_prepare($con, $deleteQuery);
                mysqli_stmt_bind_param($deleteStmt, "i", $book_id);
                mysqli_stmt_execute($deleteStmt);
    
                // Insert new author associations
                $authorQuery = "INSERT INTO books_authors (book_id, author_id) VALUES (?, ?)";
                $authorStmt = mysqli_prepare($con, $authorQuery);
    
                foreach ($authors as $author_id) {
                    mysqli_stmt_bind_param($authorStmt, "ii", $book_id, $author_id);
                    mysqli_stmt_execute($authorStmt);
                }
    
                // Ensure no single author_id in books table
                $updateSingleAuthorQuery = "UPDATE books SET author_id=NULL WHERE book_id=?";
                $stmt = mysqli_prepare($con, $updateSingleAuthorQuery);
                mysqli_stmt_bind_param($stmt, "i", $book_id);
                mysqli_stmt_execute($stmt);
            }
        }
    
        $_SESSION['status'] = 'Book updated successfully';
        header("Location: registeredBook.php");
        exit();
    }
    
    // Update Author Name in author Table
    if (isset($_POST['updateAuthor'])) {
        $author_id = $_POST['author_id'];
        $name = $_POST['name'];
    
        $query = "UPDATE author SET name=? WHERE author_id=?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "si", $name, $author_id);
    
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['status'] = 'Author updated successfully';
        } else {
            $_SESSION['status'] = 'Failed to update author: ' . mysqli_stmt_error($stmt);
        }
    
        header("Location: registered.php");
        exit();
    }
    ?>
    

<?php
//category crud
if (isset($_POST['addCategory'])) {
      $category_name = $_POST['category_name'];
      $namecheck = "SELECT category_name FROM category where category_name='$category_name'" ;
      $namecheck_run = mysqli_query($con, $namecheck);
      if (mysqli_num_rows($namecheck_run) > 0) {
            $_SESSION['status'] = 'Category name already added';
     /*  echo"<script >alert('Email already taken!!!!');</script>"; */
             header("location:categoryregisitered.php");
            exit;

     } else {
            $query = "INSERT into category(category_name)values('$category_name')";
            $sqli_result = mysqli_query($con, $query);
            if ($sqli_result) {
                  $_SESSION['status'] = 'Category added successfully';
                  header("location:categoryregisitered.php");
                 
            } else {
                  $_SESSION['status'] = 'Category addition failed';
                  header("location:categoryregisitered.php");
            }
    
      }
}
?>
<?php
//update
      if (isset($_POST['updateCategory'])) {
            $category_id = $_POST['category_id'];
            $category_name = $_POST['category_name'];

            $query = "UPDATE category set category_name='$category_name' where category_id='$category_id' ";
            $query_run = mysqli_query($con, $query);
      
      
            if ($query_run) {
                  $_SESSION['status'] = 'category updated  successfully';
                  header("location:categoryregisitered.php");
                 
            } else {
                  $_SESSION['status'] = 'category updation failed';
                  header("location:categoryregisitered.php");
            }
}

?>
<?php
if (isset($_POST["deleteCategorybtn"])) {
      $category_id = $_POST['delete_id'];
      $query = "DELETE FROM category WHERE category_id ='$category_id'";
      $query_run = mysqli_query($con, $query);
      if ($query_run) {
            $_SESSION['status'] = 'Category deleted successfully';
            header("location: categoryregisitered.php");
            exit();
      } else {
            $_SESSION['status'] = 'Category deletion failed';
            header("location: categoryregisitered.php");
            exit();
      }
}

?>
<?php
if (isset($_POST["deleteBookbtn"])) {

      $book_id = $_POST['delete_id'];
      $query = "DELETE FROM books WHERE book_id ='$book_id'";
      $query_run = mysqli_query($con, $query);
      if ($query_run) {
            $_SESSION['status'] = 'Book deleted successfully';
            header("location: registeredBook.php");
            exit();
      } else {
            $_SESSION['status'] = 'Book deletion failed';
            header("location: registeredBook.php");
            exit();
      }
}

?>

<?php 
//USERCRUD
?>


<?php
//UPDATE
if (isset($_POST['updateUser'])) {
      $id = $_POST['user_id'];
      $name = $_POST['usernamel'];
      $email = $_POST['email'];
      $phone = $_POST['phone'];
      $query = "UPDATE uuser set usernamel='$name', email='$email',phone='$phone' where user_id='$id' ";
      $query_run = mysqli_query($con, $query);


      if ($query_run) {
            $_SESSION['status'] = 'user updated  successfully';
            header("location:CustomerRegister.php");
            exit();
      } else {
            $_SESSION['status'] = 'user updation failed';
            header("location:CustomerRegister.php");
      }
}
//deleteUser
if (isset($_POST["deleteCustomerbtn"])) {
      $user_id = $_POST['delete_id'];
      $query = "DELETE from uuser where user_id ='$user_id'";
      $query_run = mysqli_query($con, $query);
      if ($query_run) {
            $_SESSION['status'] = 'customer deleted  successfully';
            header("location:CustomerRegister.php");
            exit();
      } else {
            $_SESSION['status'] = 'customer deletion failed';
            header("location:CustomerRegister.php");
      }
}
?>
<?php
session_start();
include('config/adminDbConn.php');

if (isset($_POST['updateBook'])) {
    $errors = [];
    $book_id = $_POST['book_id'];
    $title = $_POST['title'];
    $authors = $_POST['authors'];
    $description = $_POST['description'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];

    // Validate title
    if (empty($title)) {
        $errors[] = "Book name is required.";
    }
    
    // Validate authors
    if (empty($authors)) {
        $errors[] = "At least one author must be selected.";
    }

    // Validate description
    if (empty($description)) {
        $errors[] = "Description is required.";
    }

    // Validate category
    if (empty($category_id)) {
        $errors[] = "Category is required.";
    }

    // Validate price
    if ($price <= 0) {
        $errors[] = "Price must be a positive number.";
    }

    // Validate file upload
    if (!empty($_FILES['file']['name'])) {
        $file = $_FILES['file'];
        $allowedFileTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        $maxFileSize = 5 * 1024 * 1024; // 5 MB

        if (!in_array($file['type'], $allowedFileTypes)) {
            $errors[] = "Invalid file type. Only PDF and DOC files are allowed.";
        }

        if ($file['size'] > $maxFileSize) {
            $errors[] = "File size exceeds the 5 MB limit.";
        }
    }

    // Validate cover upload
    if (!empty($_FILES['cover']['name'])) {
        $cover = $_FILES['cover'];
        $allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxImageSize = 2 * 1024 * 1024; // 2 MB

        if (!in_array($cover['type'], $allowedImageTypes)) {
            $errors[] = "Invalid image type. Only JPG, PNG, and GIF files are allowed.";
        }

        if ($cover['size'] > $maxImageSize) {
            $errors[] = "Image size exceeds the 2 MB limit.";
        }
    }

    if (empty($errors)) {
        // Process the update
        // Your update query here
        
        // Redirect or show success message
        header("Location: editBook.php?book_id=$book_id&success=Book updated successfully");
        exit();
    } else {
        $_SESSION['errors'] = $errors;
        header("Location: editBook.php?book_id=$book_id");
        exit();
    }
}
?>
