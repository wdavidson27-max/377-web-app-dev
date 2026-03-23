<?php


include('library.php');

?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

        <style>
            .alert-position {
                display: none;
                position: fixed;
                left: 50%;
                margin-left: -250px;
                top: 100px;
                width: 500px;
                z-index: 10000;
        }
        </style>

        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        
        <script>

            function showAlert(type, title, message) {
            $('#alert').hide();
            $('#alert').removeClass('alert-success alert-info alert-warning alert-danger').addClass('alert-' + type);
            $('#alertTitle').text(title);
            $('#alertMessage').html(message);
            $('#alert').fadeIn();
        }
        
        </script>

        <title>Davo's Sports Database</title>
        
    </head>

    <body>
        <div id="alert" class="alert alert-position alert-success">
            <a class="close" onclick="$('#alert').fadeOut()"><span aria-hidden="true">&times;</span></a>
            <strong id="alertTitle">Success!</strong> <span id="alertMessage">Success message.</span>
        </div>

        <div class="container">
            <h1>Davos Sports Database</h1>
               
            <?php
                if (!isset($content))
                {
                    $content = "list";
                }
                include("$content.php");

            
            ?>
            
        </div>

        
    </body>
</html>