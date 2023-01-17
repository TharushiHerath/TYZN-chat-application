<?php
    include('backend/database_connection.php');

    session_start();

    if(!isset($_SESSION['user_id']))
    {
        header('location: index.php');
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TYSNchat Application</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>
<style>
   
</style>
<body>
    <div class="container">
        <form id="new_document_attachment" method="post">
            <div class="actions"><input type="submit" name="commit" id="aaa" value="Submit" /></div>
            <input type="file" id="document_attachment_doc" />
        </form>
    </div>
    
    <!-- JavaScript Files -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js"></script>

    <script>
        const form = document.getElementById("new_document_attachment");
        const fileInput = document.getElementById("document_attachment_doc");

        fileInput.addEventListener('change', () => {
            form.submit();
        });

        window.addEventListener('paste', e => {
            fileInput.files = e.clipboardData.files;
        });
        $(document).ready(function(){
            $(document).on('click', '#aaa', function(){
                var a = $('#document_attachment_doc').val();
                alert(a);
            })
        })
    </script>
    
</body>
</html>