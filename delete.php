<?php
    session_start();
    if(empty($_SESSION['username'])){
        header("location: login.php");
    }
    include('./includes/configs/DBconfig.php');
    if(isset($_GET['docid'])){
        $getDOCID=$_GET['docid'];
        $delete=mysqli_query($con,"DELETE FROM documents WHERE doc_id='$getDOCID'");
        $deleteAgreement=mysqli_query($con,"DELETE FROM agreement WHERE doc_id='$getDOCID'");
        if($delete && $deleteAgreement){
            $msg="Document deleted and it's Agreements successfully";
            header('location: documents.php?msg=' . $msg);
        }
        else{
            $msg="Failed to delete document and it's Agreements";
            header('location: documents.php?error=' . $msg); 
        }
    }
    else if(isset($_GET['agreementid'])){
        $getAgreeID=$_GET['agreementid'];
        $status='Pending';
        $Agreement=mysqli_query($con,"SELECT * FROM agreement WHERE agree_id='$getAgreeID'");
            $row = mysqli_fetch_assoc($Agreement);
            $ID = $row['doc_id'];
        $deleteAgreement=mysqli_query($con,"DELETE FROM agreement WHERE agree_id='$getAgreeID'");
        if($deleteAgreement){
            $updateStatus=mysqli_query($con,"UPDATE documents SET status='$status' WHERE doc_id='$ID'");
            if($updateStatus){
                $msg="Agreement deleted and document status updated to Pending successfully";
                header('location: documents.php?msg=' . $msg);
            } else {
                $msg="Agreement deleted but failed to update document status";
                header('location: documents.php?error=' . $msg);  
            }
        }
        else{
            $msg="Failed to delete Agreement";
            header('location: agreements.php?error=' . $msg); 
        }
    }
    else if(isset($_GET['all'])){
        $deleteALLDoc=mysqli_query($con,"TRUNCATE documents");
        $deleteAllAgree=mysqli_query($con, "TRUNCATE agreement");
        if($deleteALLDoc && $deleteAllAgree){
            $msg="All Documents and Agreements deleted successfully";
            header('location: documents.php?msg=' . $msg); 
        }
        else{
            $msg="Failed to delete all documents and Agreements";
            header('location: documents.php?error=' . $msg);
        }
    }
    else if(isset($_GET['allAgree'])){
        $deleteAllAgree=mysqli_query($con, "TRUNCATE agreement");
        $updateDocStatus=mysqli_query($con, "UPDATE documents SET status='Pending' WHERE status='Success'");
        if($deleteAllAgree && $updateDocStatus){
            $msg="All Agreements deleted successfully";
            header('location: agreements.php?msg=' . $msg); 
        }
        else{
            $msg="Failed to delete all Agreements";
            header('location: agreements.php?error=' . $msg);
        }
    }
    else{
        echo'
            <script>
                alert("Invalid request");
                window.location.href="index.php";
            </script>
        ';
    }
?>