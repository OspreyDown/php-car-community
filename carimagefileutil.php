<?php
require_once 'carimagefileconstants.php';

/**
 * purpose: Validates an uploaded car image file
 * 
 * Description: Validates an uploaded car image file is not greater than ML_MAX_FILE_SIZE (1/2 MB),
 * and is either a jpg or png image type, and has no errors. If the image file
 * validates to these constraints, an error message containing an empty string is returned.
 * If there is an error, a string containing constraints the file failed to validate are returned.
 * 
 * @return string Empty if validation is successful, otherwise error string containing
 *                constraints the image file failed to validate to.
 */
function validateCarImageFile()
{
    $error_message = "";

    // Check for $_FILES being set and no errors.
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK)
    {

        $finfo = new finfo(FILEINFO_MIME_TYPE);

        $image_type = $finfo->file($_FILES['image']['tmp_name']);

        $allowed_image_types = [
            'image/jpeg',
            'image/png',
            'image/gif'
        ];

        // Check for uploaded file < Max file size AND an acceptal image type
        if ($_FILES['image']['size'] > MAX_FILE_SIZE)
        {
            $error_message = "The file must be less than " . MAX_FILE_SIZE . " Bytes";
        }
        // Validate actual MIME type
        elseif (!in_array($image_type, $allowed_image_types, true)) {
            $error_message =
                "The file must be a JPG, PNG, or GIF image.";
        }
    }
    elseif (isset($_FILES) && $_FILES['image']['error'] != UPLOAD_ERR_NO_FILE
        && $_FILES['image']['error'] != UPLOAD_ERR_OK)
    {
        $error_message = "Error uploading you car picture!.";
    }

    return $error_message;
}

/**
 * Purpose:
 * 
 * Description: Moves an uploaded car image file from the temporary server
 * location to the ML_UPLOAD_PATH (images/) folder IF a car image file was uploaded
 * and returns the path location of the uploaded file by appending the file
 * name to the ML_UPOAD_PATH (e.g. images/car_image.png). IF a car image
 * file was NOT uploaded, an empty string will be returned for the path.
 *
 * @return string Path to car image file IF a file was uploaded AND moved to the
 *                ML_UPLOAD_PATH (images/) folder, otherwise and empty string.
 */
function addcarImageFileReturnPathLocation()
{
    $image = "";

    // Check for $_FILES being set and no errors
    if (isset($_FILES) && $_FILES['image']['error'] === UPLOAD_ERR_OK)
    {

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $image_type = $finfo->file($_FILES['image']['tmp_name']);

        $extensions = [
            'image/jpeg' => '.jpg',
            'image/png'  => '.png',
            'image/gif'  => '.gif'
        ];
        
        if (isset($extensions[$image_type])) {

            $file_name = bin2hex(random_bytes(16)) .
                         $extensions[$image_type];
            
            $image = UPLOAD_PATH . $file_name;

        }

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $image))
        {
            $image = "";
        }
    }

    return $image;
}

/**
 * @param $image
 */
function removeCarImageFile($image)
{
    @unlink($image);
}
?>