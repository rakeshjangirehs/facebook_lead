<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Facebook Lead Manager</title>
  </head>

  <body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top">
        <div class="container">
        <a class="navbar-brand" href="#">Facebook</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
                <a class="nav-link" href="<?php echo base_url()."index.php/home/";?>">Home
                <span class="sr-only">(current)</span>
                </a>
            </li>
            <?php if(!$is_in){ ?>

            <?php }else{ ?>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url()."index.php/home/my_pages";?>">My Pages</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url()."index.php/home/wehooks";?>">Web Hooks</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $logOutUrl; ?>">Logout</a>
            </li>
            <?php } ?>
            </ul>
        </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="container">
        <div class="row">
            <?php if(!$is_in){ ?>
                <div class="col-lg-12 text-center">
                    <a href="<?php echo $authURL; ?>"><img src="<?php echo base_url('assets/images/fb-login-btn.png'); ?>"></a>
                    <a href="<?php echo base_url().'webhook_log.txt'; ?>" target='_blank'>View Logs</a>
                </div>
            <?php }else{ ?>
            <div class="col-lg-12 text-center">
                <h1 class="mt-5">Facebook Profile Details</h1>
                <p class="lead"><img src="<?php echo $picture; ?>"/></p>
                <ul class="list-unstyled">
                <li>Facebook ID: <?php echo $oauth_uid; ?></li>            
                <li>Name: <?php echo $first_name.' '.$first_name; ?></li>                   
                <li>Email: <?php echo $email; ?></li>                   
                <li>Gender: <?php echo $gender; ?></li>
                <li><a href="<?php echo base_url().'webhook_log.txt'; ?>" target='_blank'>View Logs</a></li>
                </ul>
            </div>
            <?php } ?>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>