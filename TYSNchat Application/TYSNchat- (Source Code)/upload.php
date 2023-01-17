<?php

//upload.php

    $folder_name = 'uploads/';
    if(!empty($_FILES))
    {
        $tmpFile = $_FILES['file']['tmp_name'];
        $filename = $folder_name.'/'.time().'-'. $_FILES['file']['name'];
        move_uploaded_file($tmpFile,$filename);
    }
    if(isset($_POST["name"]))
    {
     $filename = $folder_name.$_POST["name"];
     unlink($filename);
    }
    
    $result = array();
    
    $files = scandir('uploads');
    
    $output = '<div>';
    
    if(false !== $files)
    {
     foreach($files as $file)
     {
      if('.' !=  $file && '..' != $file)
      {
       $output .= '
       <div class="col-md-2">
        <img src="'.$folder_name.$file.'" class="img-thumbnail" width="175" height="175" style="height:175px;" />
        <button type="button" class="btn btn-link remove_image" id="'.$file.'">Remove</button>
       </div>
       ';
      }
     }
    }
    $output .= '</div>';
    echo $output;
?>