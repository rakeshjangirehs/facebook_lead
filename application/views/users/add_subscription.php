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
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url()."index.php/home/";?>">Home
                <span class="sr-only">(current)</span>
                </a>
            </li>
            <?php if(!$is_in){ ?>

            <?php }else{ ?>
            <li class="nav-item active">
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
        <?php if($this->session->flashdata('error')):?>
        <div class="row">
            <div class="col-lg-12 text-center">                    
                <div class='alert alert-danger' role='alert' style='word-break: break-word;'>
                    <?php echo $this->session->flashdata('error');?>d
                </div>                    
            </div>
        </div>
        <?php elseif($this->session->flashdata('success')): ?>
        <div class="row">
            <div class="col-lg-12 text-center">                    
                <div class='alert alert-success' role='alert' style='word-break: break-word;'>
                <?php echo $this->session->flashdata('success');?>s
                </div>                    
            </div>
        </div>
        <?php endif;?>
        <div class="row">
            <div class="col-lg-12">
                <div class="jumbotron">
                    <h1 class="display-5">Add/ Update Subscription</h1>
                    <form method="post">
                        <div class="form-group">
                            <label for="fields">Fields</label>
                            <input type="text" class="form-control" id="fields" name="fields" aria-describedby="objectHelp" placeholder="Enter comma seprated list of fields" value="<?php echo $subscribed_fiedls;?>"/>
                            <small id="objectHelp" class="form-text text-muted"></small>
                        </div>
                        
                        <input type="hidden" name="page_access_token" value="<?php echo $page_access_toekn;?>"/>
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href='<?php echo base_url()."index.php/home/remove_permission/{$page_id}";?>' class='btn btn-danger'>Remove Subscription</a>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <ul class="list-group">
                <?php foreach(explode(",",$subscribed_fiedls) as $field){                    
                    echo "<li class='list-group-item d-flex justify-content-between align-items-center'>{$field}</li>";
                }?>
                </ul>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>