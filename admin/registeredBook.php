<?php
session_start();
if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
include('includes/header.php');
include('includes/topbar.php');
include('includes/sidebar.php');
include('config/adminDbConn.php');
include('Function.php');
$author = get_authors($con);

$category = get_category($con);
include('uploadFile.php')
?>
<div class="content-wrapper">
    <!-- Modal -->
    <div class="modal fade" id="AddUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Books</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                <span aria-hidden="true">&times;</span>
            </div>
            <form action="adminCode.php" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" name="name" class="form-control" placeholder="Book Name">
                    </div>
                    <div class="form-group">
                        <select name="authors[]" class="form-control" multiple>
                            <option value="0">Select Authors</option>
                            <?php
                            if ($author != 0) {
                                foreach ($author as $authors) {
                                    ?>
                                    <option value="<?= $authors['author_id'] ?>">
                                        <?= $authors['name'] ?>
                                    </option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" name="description" class="form-control" placeholder="Description">
                    </div>
                    <div class="form-group">
                        <select name="category" class="form-control">
                            <option value="0">Select Category</option>
                            <?php
                            if ($category != 0) {
                                foreach ($category as $categories) {
                                    ?>
                                    <option value="<?= $categories['category_id'] ?>">
                                        <?= $categories['category_name'] ?>
                                    </option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-group">Book Pdf</label>
                        <input type="file" name="file" class="form-control" placeholder="File">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Book Cover</label>
                        <input type="file" name="cover" class="form-control" placeholder="Cover">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Price</label>
                        <input type="text" name="price" class="form-control" placeholder="Price">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="addBooks" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <!--DeleteBookss-->
    <div class="modal fade" id="DeleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Book</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    <span aria-hidden="true">&times;</span>
                </div>
                <form action="adminCode.php" method="post">
                    <div class="modal-body">
                        <input type="text" name="delete_id" value="" class="delete_book_id">
                        <p>Are you sure You want to delete the book?</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="deleteBookbtn" class="btn btn-primary">YEs,Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Dashboard</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active"><a href="#">Resistered Books</a></li>

                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">


                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Registered Books

                            </h3>
                            <a href="" data-toggle="modal" data-target="#AddUserModal"
                                class="btn btn-primary btn-sm float-right">Add Books</a>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Book Name</th>
                                        <th>Author</th>
                                        <th>Description</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Action</th>
                                       

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "select * from books";
                                    $query_run = mysqli_query($con, $query);
                                    if (mysqli_num_rows($query_run) > 0) {
                                        foreach ($query_run as $row) {

                                            ?>
                                            <tr>
                                                <td>
                                                <?php echo $row['book_id']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['title']; ?>
                                                    <img width="160" height="200" src="../uploads/cover/<?= $row['cover'] ?>">
                                                    <a class="link-dark d-block text-center" 
                                                    href="../uploads/files/<?= $row['file']; ?>">
                                                </td>
                                                <td>
                                                    <?php if ($author == 0) {
                                                        echo 'undefined';
                                                    } else {
                                                        foreach ($author as $authors) {
                                                            if ($authors['author_id'] == $row['author_id']) {
                                                                echo $authors['name'];
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['description']; ?>
                                                </td>

                                                <td>
                                                    <?php if ($category == 0) {
                                                        echo 'undefined';
                                                    } else {
                                                        foreach ($category as $categories) {
                                                            if ($categories['category_id'] == $row['category_id']) {
                                                                echo $categories['category_name'];
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                <?php echo $row['price']; ?>
                                                
                                                </td>
                                                <td>
                                                    <a href="book-edit.php?book_id=<?php echo $row['book_id']; ?>"
                                                        class="btn btn-info btn-sm">Edit</a>
                                                      
                                                            
                                                        <button type="button" value="<?php echo $row['book_id']; ?>"
                                                        class="btn btn-danger btn-sm deletebtn"  >Delete</button>

                                                        
                                                </td>


                                            </tr>

                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td>NO record Found</td>
                                        </tr>
                                        <?php
                                    }

                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>
<?php
include('includes/footer.php');
?>
<script>

    $(document).ready(function () {

        $('.deletebtn').click(function (e) {
            e.preventDefault();
            var book_id = $(this).val();
            $('.delete_book_id').val(book_id);
            $('#DeleteModal').modal('show');
        });
    });
</script>
<?php
include('includes/script.php');
?>
<?php
}
else{
    header("location:../LoginForm.php");
}
?>