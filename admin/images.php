<html>
<head>
    <title>Image Admin</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <link href="css/style.css" rel="stylesheet">

</head>
<body>

    <div class="topNav">
        <a id="mobileMenu" href="javascript:;"><img src="icons/menu.svg" alt="menu"/></a>
        <div class="topNavInner">
            <nav>
                <a class="navLink navHomeLink" href="index.php">Home</a>
                <form class="generateSizes floatRight mt5" method="post"><input type="hidden" name="generateSizes" value="true"><input class="bgWhite darkBorder" type="submit" value="Generate Sizes"></form>
            </nav>
        </div>
    </div>

    <div id="imagePage" class="wrapper">

        <form class="uploadForm" method="post" enctype="multipart/form-data" name="formUploadFile">  
            <div class="formElement">    
                <label for="imageFiles">Select file(s) to upload:</label>
                <input id="imageFiles" type="file" name="files[]" multiple="multiple" required />
            </div>
            <input type="submit" value="Upload" name="btnSubmit"/>
        </form> 


<?php
    // basic code from http://www.javascripthive.info/php/php-and-jquery-multiple-files-upload-progressbar-validation/

    $uploadFolder = "../images";
    $imageSizeArray = ['thumbs','medium','large'];

    // create image folders if they don't exist
    if (!file_exists($uploadFolder)) {
        mkdir($uploadFolder, 0755, true);
    }

    foreach ($imageSizeArray as $imageSize) {
        $imageSizeFolder = $uploadFolder . "/" . $imageSize;
        if (!file_exists($imageSizeFolder)) {
            mkdir($imageSizeFolder, 0755, true);
        }       
    }

    function make_size($src, $dest, $desired_width, $ext, $overWrite) {

        if (!file_exists($dest) || $overWrite) {

            /* read the source image */
            if ($ext == "png") {
                $source_image = imagecreatefrompng($src);
            } elseif ($ext == "gif") {
                $source_image = imagecreatefromgif($src);
            } else {
                $source_image = imagecreatefromjpeg($src);
            }
            $width = imagesx($source_image);
            $height = imagesy($source_image);

            /* find the "desired height" of this thumbnail, relative to the desired width  */
            $desired_height = floor($height * ($desired_width / $width));

            /* create a new, "virtual" image */
            $newImg = imagecreatetruecolor($desired_width, $desired_height);

            imagealphablending($newImg, false);
            imagesavealpha($newImg, true);
            $transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
            imagefilledrectangle($newImg, 0, 0, $desired_width, $desired_height, $transparent);

            /* copy source image at a resized size */
            imagecopyresampled($newImg, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

            /* create the physical thumbnail image to its destination */
            if ($ext == "png") {
                imagepng($newImg, $dest);
            } elseif ($ext == "gif") {
                imagegif($newImg, $dest);
            } else {
                imagejpeg($newImg, $dest);
            }

            return true;

        }

        return false;

    }

    function make_sizes($uploadFolder, $name, $src, $ext, $overWrite) {

        $imageCount = 0;

        //make thumbnails
        $dest = $uploadFolder."/thumbs/".$name;
        $desired_width = "200";
        $sizeGenerated = make_size($src, $dest, $desired_width, $ext, $overWrite);
        if ($sizeGenerated) { $imageCount++; }

        //make medium
        $dest = $uploadFolder."/medium/".$name;
        $desired_width = "600";
        $sizeGenerated = make_size($src, $dest, $desired_width, $ext, $overWrite);
        if ($sizeGenerated) { $imageCount++; }

        //make large
        $dest = $uploadFolder."/large/".$name;
        $desired_width = "1200";
        $sizeGenerated = make_size($src, $dest, $desired_width, $ext, $overWrite);
        if ($sizeGenerated) { $imageCount++; }

        //Add more sizes here. Don't forget to...
        //make size folders
        //add size to $imageSizeArray (so it image can be deleted)
        //add to dropdown in function_general.php (printImageList)

        return $imageCount;

    }

    if (isset($_POST["btnSubmit"])) {
        $errors = array();
        $uploadedFiles = array();
        $extension = array("jpeg","jpg","png","gif");
        $KB = 1024;
        $MB = 1024 * $KB;
        $MBs = 2;
        $totalBytes = $MB * $MB;
        
        $counter = 0;


        foreach ($_FILES["files"]["tmp_name"] as $key=>$tmp_name) {
            $temp = $_FILES["files"]["tmp_name"][$key];
            $name = $_FILES["files"]["name"][$key];
            
            if (empty($temp)) {
                break;
            }
            
            $counter++;
            $UploadOk = true;
            
            if ($_FILES["files"]["size"][$key] > $totalBytes) {
                $UploadOk = false;
                array_push($errors, $name." file size is larger than the " . $MBs . " MB.");
            }
            
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            $ext = strtolower($ext);
            if (in_array($ext, $extension) == false) {
                $UploadOk = false;
                array_push($errors, $name." is invalid file type.");
            }
            
            if (file_exists($uploadFolder."/".$name) == true) {
                $UploadOk = false;
                array_push($errors, $name." file already exists.");
            }
            
            if ($UploadOk == true) {
                move_uploaded_file($temp,$uploadFolder."/".$name);
                array_push($uploadedFiles, $name);

                $src = $uploadFolder."/".$name;
                make_sizes($uploadFolder, $name, $src, $ext, true);
            }
        }
        
        if ($counter>0) {
            if (count($errors)>0) {
                echo '<div class="results">';
                echo '<a class="closeResults close button" href="javascript:;"><img src="icons/cross.svg" alt="close"></a>';
                echo "<b>Errors:</b>";
                echo "<br/><ul>";
                foreach ($errors as $error) {
                    echo "<li>".$error."</li>";
                }
                echo "</ul><br/>";
                echo '</div>';
            }
            
            if (count($uploadedFiles)>0) {
                echo '<div class="results">';
                echo '<a class="closeResults close button" href="javascript:;"><img src="icons/cross.svg" alt="close"></a>';
                echo "<b>Uploaded Files:</b>";
                echo "<br/><ul>";
                foreach ($uploadedFiles as $fileName) {
                    echo "<li>".$fileName."</li>";
                }
                echo "</ul><br/>";              
                echo count($uploadedFiles)." file(s) are successfully uploaded.";
                echo '</div>';
            }                               
        } else {
            echo '<p class="error">Please, Select file(s) to upload.</p>';
        }
    }

    if (isset($_POST["generateSizes"])) {

        $files = scandir($uploadFolder . '/');
        $imageCount = 0;
        foreach ($files as $file) {
            if ($file != "." && $file != ".." && !in_array($file,$imageSizeArray)) {
                $name = $file;
                $ext = pathinfo($name, PATHINFO_EXTENSION);
                $ext = strtolower($ext);
                $src = $uploadFolder."/".$name;
                $makeSizes = make_sizes($uploadFolder, $name, $src, $ext, false);
                $imageCount = $imageCount + $makeSizes;
            }
        }
        echo '<div class="results">';
        echo '<a class="closeResults close button" href="javascript:;"><img src="icons/cross.svg" alt="close"></a>';
        echo '<p>' . $imageCount . ' Images were resized.</p>';
        echo '</div>';

    }

    if (isset($_POST["deleteImage"])) {
        // Delete Main
        unlink($uploadFolder . '/' . $_POST["deleteImage"]);
        // Delete other sizes
        foreach ($imageSizeArray as $imageSize) {
            $file = $uploadFolder . '/' . $imageSize . '/' . $_POST["deleteImage"];
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }
?>

<?php
    //$dir = "../images/";

    $files = scandir($uploadFolder . '/');
    $fileArray = [];

    $i = 0;
    foreach ($files as $file) {
        if ($file != "." && $file != ".." && !in_array($file,$imageSizeArray)) {
            $fileDate = (filemtime($uploadFolder . '/' . $file) + $i);
            $fileArray[$fileDate] = $file;
            $i++;
        }
    }
    krsort($fileArray);

    echo '<ul class="imagePageList">';

    foreach ($fileArray as $fileKey => $file) {
        echo '<li>';
        echo '<img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" class="lazy" data-src="' . $uploadFolder . "/thumbs/" . $file . '" alt="image ' . $i . '"/>';
        echo '<p><span class="name">' . $file . '</span><span class="date">(' . date("F d Y H:i:s.", $fileKey) . ")</span></p>";
        echo '<form class="deleteForm" method="post"><input type="hidden" name="deleteImage" value="' . $file . '"><input type="submit" value="Delete"></form>';
        echo '</li>';
    }

    echo "</ul>";

?>

        <p class="mt30 center italic">If you upload images via FTP use the "Generate Size" button to generate the various image sizes.</p>
    </div>

    <script>
        $(".deleteForm").submit( function(e) {
            e.preventDefault();
            var r = confirm("Are you sure?");
            if (r == true) {
              this.submit();
            }
        });

        $(".closeResults").click( function() {
            $(this).parent().fadeOut();
        })
    </script>
    <script src="js/general.js"></script>

    </body>
</html>