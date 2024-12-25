<?php
session_start();
if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
include('includes/header.php');
include('includes/topbar.php');
include('includes/sidebar.php');
include('config/adminDbConn.php');

?>



<div class="content-wrapper">
    <!-- Modal -->
    <div class="modal fade" id="AddUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Author</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    <span aria-hidden="true">&times;</span>
                </div>
                <form action="adminCode.php" method="post">
                    <div class="modal-body">
                        <!--  <div class="form-group">
                                <input type="text" name="name" class="form-control" placeholder="Name">
                            </div> -->
                        <div class="form-group">
                            <input type="text" name="name" class="form-control" placeholder="name">
                        </div>
                     <!--    <div class="form-group">
                            <input type="email" name="email" class="form-control" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <input type="text" name="phone" class="form-control" placeholder="Phone">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="password" name="password" class="form-control" placeholder="Password">
                                </div> -->
                        <!--     </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="password" name="confirmpassword" class="form-control"
                                        placeholder="Confirm Password">
                                </div>
                            </div>
                        </div> -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="addAuthor" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--DeleteAuthor-->
    <div class="modal fade" id="DeleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Author</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    <span aria-hidden="true">&times;</span>
                </div>
                <form action="adminCode.php" method="post">
                    <div class="modal-body">
                        <input type="text" name="delete_id" value="" class="delete_author_id">
                        <p>Are you sure You want to delete the data?</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="DeleteAuthorbtn" class="btn btn-primary">YEs,Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-orange">Dashboard</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active"><a href="registered.php">Resistered Authors</a></li>

                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php
                    if (isset($_session['status'])) {
                        echo "<h3>" . $_session['status'] . "</h3>";
                        unset($_SESSION['status']);
                    }
                    ?>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Registered Authors

                            </h3>
                            <a href="" data-toggle="modal" data-target="#AddUserModal"
                                class="btn btn-primary btn-sm float-right">Add Authors</a>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                       <!--  <th>Email</th>
                                        <th>Password</th>
                                        <th>Phone</th> -->
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "select * from author";
                                    $query_run = mysqli_query($con, $query);
                                    if (mysqli_num_rows($query_run) > 0) {
                                        foreach ($query_run as $row) {
                                            //      $ row['name']
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php echo $row['author_id']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['name']; ?>
                                                </td>
                                             
                                                <td>
                                                    <a href="registered-edit.php?author_id=<?php echo $row['author_id']; ?>"
                                                        class="btn btn-info btn-sm">Edit</a>
                                                    <button type="button" value="<?php echo $row['author_id']; ?>"
                                                        class="btn btn-danger btn-sm deletebtn">Delete</button>
                                                <td>


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
            var author_id = $(this).val();
            //console.log(author_id);
            $('.delete_author_id').val(author_id);
            $('#DeleteModal').modal('show');
        });
    });
</script>
<?php
include('includes/script.php');
?>
<?php 
}
else
{
header("location:../LoginForm.php");
}
?>