<?php
    session_start();
    if (empty($_SESSION['username'])) {
        header("location: login.php");
    }
    include('./includes/configs/DBconfig.php');
    $page_title = 'New Doc | Document Management System';
    include('./includes/pages/header.php');
?>

    <div class="form-box">
        <div class="form">
            <div class="form-header">
                <h1>New Doc</h1>
            </div>
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="text" name="doc_name" placeholder="Document Name" required>

                <!-- Browse Document Button -->
                <label for="file">
                    <input type="file" name="pdf_file" id="file" accept="application/pdf" style="display: none;" onchange="previewFile()">
                    <button type="button" onclick="document.getElementById('file').click()" class="browse">Browse Document</button>
                </label>

                <!-- Capture Document Button -->
                <button type="button" onclick="openCamera()" class="capture">Capture Document</button>

                <!-- Hidden input for captured image -->
                <input type="hidden" name="captured_image" id="captured_image">

                <!-- Save & Upload Button -->
                <div class="btn">
                    <button name="save">Save & Upload</button>
                </div>
            </form>
        </div>
    </div>

<?php
if (isset($_POST['save'])) {
    $doc_name = $_POST['doc_name'];
    $status = 'Pending';
    $fileDestination = '';
    $fileName = '';
    $fileSize = 0;

    // Handle uploaded PDF file
    if (!empty($_FILES['pdf_file']['name'])) {
        $fileName = $_FILES['pdf_file']['name'];
        $fileTmp_name = $_FILES['pdf_file']['tmp_name'];
        $fileSize = $_FILES['pdf_file']['size'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = array('pdf');

        if (in_array($fileExt, $allowed) && $fileSize >= 1024 && $fileSize <= 104857600) {
            $fileNewName = uniqid($doc_name, true) . "." . $fileExt;
            $fileDestination = 'DOC_uploads/' . $fileNewName;

            if (!move_uploaded_file($fileTmp_name, $fileDestination)) {
                die('<div class="msgS error">Error: File upload failed.</div>');
            }
        } else {
            die('<div class="msgS error">Invalid file format or size.</div>');
        }
    }
    // Handle captured image (convert to PDF)
    elseif (!empty($_POST['captured_image'])) {
        $imageData = $_POST['captured_image'];
        $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
        $imageData = str_replace(' ', '+', $imageData);
        $imageBinary = base64_decode($imageData);

        // Save the image temporarily
        $imagePath = 'DOC_uploads/' . uniqid('img_', true) . '.jpg';
        file_put_contents($imagePath, $imageBinary);

        // Convert the image to PDF
        require('fpdf/fpdf.php');
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->Image($imagePath, 10, 10, 190); // Add the image to the PDF
        $pdfPath = 'DOC_uploads/' . uniqid('doc_', true) . '.pdf';
        $pdf->Output('F', $pdfPath);

        // Delete the temporary image
        unlink($imagePath);

        // Set file details for database
        $fileName = basename($pdfPath);
        $fileSize = filesize($pdfPath);
        $fileDestination = $pdfPath;
    } else {
        die('<div class="msgS error">No file selected.</div>');
    }

    // Insert data into the database
    $query = mysqli_query($con,"INSERT INTO documents (doc_id, doc_name, name, size, file_path, status, created_at, updated_at) VALUES (NULL, '$doc_name', '$fileName', '$fileSize', '$fileDestination', '$status', NOW(), NOW())");

    if ($query) {
        $msg=' Document uploaded successfully!';
        header('location: documents.php?msg=' . $msg);
    } else {
        echo '<div class="msgS error"><p>Database error: ' . mysqli_error($con) . '</p></div>';
    }
    
}

include('./includes/pages/footer.php');
?>