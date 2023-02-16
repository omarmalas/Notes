<?php
//this is converted from the C# code directly 
public function onPostUpload($fileUpload)
{
    $allowed = true;

    // Store file outside the web root
    $fullPath = "D:\\CVUploads\\";

    $formFile = $fileUpload->formFile;

    // Create a GUID for the file name
    $id = uniqid();
    $filePath = $fullPath . $id . ".pdf";

    // Validate the content type
    $contentType = explode('/', $fileUpload->contentType)[1];
    $contentType = strtolower($contentType);
    if ($contentType !== "pdf") {
        $allowed = false;
    }

    // Validate the content extension
    $contentExtension = pathinfo($fileUpload, PATHINFO_EXTENSION);
    if ($contentExtension !== "pdf") {
        $allowed = false;
    }

    // Validate the content size
    $contentSize = $fileUpload->contentLength;
    // 10Mb max file size
    $maxFileSize = 10 * 1024 * 1024;
    if ($contentSize > $maxFileSize) {
        $allowed = false;
    }

    // Scan the content for malware
    $clam = new ClamClient($this->configuration["ClamAVServer:URL"], (int) $this->configuration["ClamAVServer:Port"]);
    $scanResult = $clam->sendAndScanFileAsync($fileBytes);

    if ($scanResult->result === ClamScanResults::VirusDetected) {
        $allowed = false;
    }

    // Only upload if all checks are passed
    if ($allowed) {
        $stream = fopen($filePath, 'w');
        fwrite($stream, $formFile);
        fclose($stream);
    }
}

?>
//////////////////////////////////////////////
<?php
//This includes the file upload functionality.
// Check if the form has been submitted
if (isset($_POST['submit'])) {
    // Get the file from the form
    $formFile = $_FILES['file'];

    // Set the target directory and file path
    $targetDir = "uploads/";
    $filePath = $targetDir . basename($formFile['name']);

    // Initialize a flag to check if the file is allowed to be uploaded
    $allowed = true;

    // Validate the content type
    $contentType = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    if ($contentType !== "pdf") {
        // Set the flag to false if the content type is not allowed
        $allowed = false;
    }

    // Validate the content size
    $contentSize = $formFile['size'];
    // 10Mb max file size
    $maxFileSize = 10 * 1024 * 1024;
    if ($contentSize > $maxFileSize) {
        // Set the flag to false if the content size is too large
        $allowed = false;
    }

    // Scan the content for malware
    // This requires the ClamAV library, which you will need to install and configure
    $clam = new ClamAV();
    $scanResult = $clam->scan($formFile['tmp_name']);
    if ($scanResult === ClamAV::VIRUS_FOUND) {
        // Set the flag to false if a virus is detected
        $allowed = false;
    }

    // Only upload if all checks are passed
    if ($allowed) {
        if (move_uploaded_file($formFile['tmp_name'], $filePath)) {
            // Display a success message if the file was successfully uploaded
            echo "The file " . basename($formFile['name']) . " has been uploaded.";
        } else {
            // Display an error message if there was an issue uploading the file
            echo "Sorry, there was an error uploading your file.";
        }
    }
}


/*This code first checks if the form has been submitted, and if it has, it gets the file from the form and sets the target directory and file path. It then performs several checks on the file, including validating the content type, size, and scanning it for malware. If the file passes all of these checks, it is uploaded to the server using the move_uploaded_file function. If any of the checks fail, the file is not uploaded. Finally, the code displays a form for the user to select a file to upload.*/

?>

<!-- Display a form for the user to select a file to upload -->
<form action="" method="post" enctype="multipart/form-data">
    <input type="file" name="file">
    <input type="submit" value="Upload" name="submit">
</form>
