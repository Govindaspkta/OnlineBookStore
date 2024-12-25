<?php
session_start();
include('includes/header.php');
include('includes/topbar.php');
include('includes/sidebar.php');
include('config/adminDbConn.php');
?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active"><a href="#">Edit-Registered Authors</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Edit-Registered Authors</h3>
                            <a href="registered.php" class="btn btn-danger btn-sm float-right">Back</a>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <form action="adminCode.php" method="post">
                                        <div class="modal-body">
                                            <?php
                                            if (isset($_GET['author_id'])) {
                                                $author_id = $_GET['author_id'];
                                                $query = "SELECT * FROM author WHERE author_id='$author_id' LIMIT 1";
                                                $result = mysqli_query($con, $query);
                                                if (mysqli_num_rows($result) > 0) {
                                                    foreach ($result as $row) {
                                                        ?>
                                                        <div class="form-group">
                                                            <input type="hidden" name="author_id" value="<?php echo $row['author_id']; ?>">
                                                            <label for="name">Author Name</label>
                                                            <input type="text" name="name" value="<?php echo $row['name']; ?>" class="form-control" placeholder="Name">
                                                        </div>
                                                        <?php
                                                    }
                                                } else {
                                                    echo "<h3>No Record Found.</h3>";
                                                }
                                            }
                                            ?>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" name="updateAuthor" class="btn btn-info">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
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
