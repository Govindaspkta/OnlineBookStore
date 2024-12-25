<!-- <?php
function get_books($con){
    $sql = "select * from book order by book_id desc";
   $book_conn=mysqli_query($con,$sql);
   if(mysqli_num_rows($book_conn)> 0){
    $book=mysqli_fetch_assoc($book_conn);
   

}
else{
    $book=0;
}
return $book;
}

?>