<?php

if (isset($_POST['submit'])) {
    


    if (isset($_FILES['file'])) {
        $file = $_FILES['file'];

        $fileNames = $_FILES['file']['name'];
        $fileTmpNames = $_FILES['file']['tmp_name'];
        $fileSizes = $_FILES['file']['size'];
        $fileErrors = $_FILES['file']['error'];
        $fileTypes = $_FILES['file']['type'];
        session_start();
        require_once('dbConfig.inc.php');
      
       

      


            for ($i = 0; $i < count($fileNames); $i++) {

                $fileName = $fileNames[$i];
                $fileTmpName = $fileTmpNames[$i];
                $fileSize = $fileSizes[$i];
                $fileError = $fileErrors[$i];
                $fileType = $fileTypes[$i];


                $fileExt = explode('.', $fileName);
                $fileActualExt = strtolower(end($fileExt));
                $allowed = array('jpg', 'jpeg', 'png');
                if (in_array($fileActualExt, $allowed) && ($filetype=='image/jpeg' || $filetype=='image/jpg' ||$filetype=='image/png') && getimagesize($fileTmpName)) {
                    if ($fileError === 0) {
                        if ($fileSize < 100000) {

                        // All tests passed
                            $temp_file = (( ini_get('upload_tmp_dir')== '') ? ( sys_get_temp_dir()) : ( ini_get('upload_tmp_dir')));
                            $temp_file .= DIRECTORY_SEPARATOR  . md5(uniqid().$fileName). '.' . $fileActualExt;
                            if($filetype=='image/jpeg'){
                                $img=imagecreatefromjpeg($fileTmpName);
                                imagejpeg($img,$temp_file,100);
                            }

                            else if($filetype=='image/png'){
                                $img=imagecreatefrompng($fileTmpName);
                                imagepng($img,$temp_file,9);
                            }
                            imagedestroy($img); 

                            $fileNameNew = md5(uniqid() . $fileName) . "." . $fileActualExt;
                            $fileDestination = '../uploaded-images/' . $section . '/' . $fileNameNew;
                            move_uploaded_file($fileTmpName, $fileDestination);
                            
                            if( rename( $temp_file, ( getcwd() . DIRECTORY_SEPARATOR . $target_path . $target_file ) ) ) {
                                // Yes!
                                echo '<script>alert("<a href="${target_path}${target_file}">${target_file}</a> succesfully uploaded!")</script>';
                            }




                           
                        } else {
                            echo '<script>alert("Your file is too big");document.location="../site-admin/upload-image.php"</script>';
                        }
                    } else {
                        echo '<script>alert("There was an error during upload");document.location="../site-admin/upload-image.php"</script>';
                    }
                } else {
                    echo '<script>alert("You Cannot upload image of this format");document.location="../site-admin/upload-image.php"</script>';
                }
            }
        } else {

            echo  '<script>alert("update to database failed");document.location="../site-admin/upload-image.php"</script>';
        }
    } else {
        echo '<script>alert("No files Selected");document.location="../site-admin/upload-image.php"</script>';
    }

