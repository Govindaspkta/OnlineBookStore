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
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active"><a href="#">Edit-Registered Categories</a></li>

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
                            <h3 class="card-title">Edit-Registered Categories

                            </h3>
                            <a href="categoryregisitered.php" class="btn btn-danger btn-sm float-right">Back</a>
                        </div>
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-6">
                                    <form action="adminCode.php" method="post">

                                        <div class="modal-body">
                                            <?php
                                            if (isset($_GET['category_id'])) {
                                                $category_id = $_GET['category_id'];
                                                $query = "select * from category where category_id='$category_id' limit 1";
                                                $result = mysqli_query($con, $query);
                                                if (mysqli_num_rows($result) > 0) {
                                                    foreach ($result as $cat) {
                                                        ?>

                                                        <div class="form-group">
                                                            <input type="hidden" name="category_id"
                                                                value="<?php echo $cat['category_id'] ?>">
                                                            <input type="text" name="category_name" value="<?php echo $cat['category_name'] ?>"
                                                                class="form-control" placeholder="name">
                                                        </div>
                                                       <!--  <div class="form-group">
                                                            <input type="password" name="password"
                                                                value="<?php echo $row['password'] ?>" class="form-control"
                                                                placeholder="Password">
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="email" name="email" value="<?php echo $row['email'] ?>"
                                                                class="form-control" placeholder="Email">
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="text" name="phone" value="<?php echo $row['phone'] ?>"
                                                                class="form-control" placeholder="Phone">
                                                        </div> -->
                                                        <?php
                                                    }
                                                } else {
                                                    echo "<h3>NO Record Found.</h3>";
                                                }
                                            }
                                            ?>


                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" name="updateCategory"
                                                class="btn btn-info">Update</button>
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