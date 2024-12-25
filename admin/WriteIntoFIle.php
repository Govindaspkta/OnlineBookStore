<?php
if(isset($_POST["save"])){
    $filename=$_post['filename'];
    $ext=$_post['extension'];
    $text=$_post['data'];
    $file=$filename.$ext;
    if(file_exists($file)){
        echo"File alreadyt exists";

}
else{
    $fopen=fopen($file,"w");
    fwrite($fo,$data);
    echo"YOur data is saved";
}
}
?>
<hmtl>
    <form method="post">
        <input type="text"  name='filename' placeholde="File Name Here!!!!">
        <br>
        Choose Extension
        <select name="extension">
            <option value="">Choose Extension</option>
            <option>.txt</option>
            <option>.doc</option>
            <option>.pdf</option>
        </select>
    <textarea rows="20" column="20" placeholder="your text here!!" name="data" >
</textarea>

<br>
<input type="submit" value="save">

    </form>