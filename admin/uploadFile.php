// uploadFile.php
<?php
function uploadFile($file, $path) {
    $file_name = $file['name'];
    $tmp_name = $file['tmp_name'];
    $error = $file['error'];

    // Define allowed extensions based on file type
    $allowed_exts = [
        'pdf' => ['pdf'],
        'cover' => ['jpg', 'jpeg', 'png']
    ];

    if ($error == UPLOAD_ERR_OK) {
        $file_ex = pathinfo($file_name, PATHINFO_EXTENSION);
        $file_ex_lowercase = strtolower($file_ex);

        $type = ($path === '../uploads/files/') ? 'pdf' : 'cover';

        if (in_array($file_ex_lowercase, $allowed_exts[$type])) {
            $upload_directory = $path;
            $file_upload_path = $upload_directory . $file_name;

            if (move_uploaded_file($tmp_name, $file_upload_path)) {
                return $file_name;
            } else {
                return false;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}
?>
