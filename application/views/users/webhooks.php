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
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url()."index.php/home/my_pages";?>">My Pages</a>
            </li>
            <li class="nav-item active">
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
                    <h1 class="display-5">Configure Webhook</h1>
                    <form method="post" action="<?php echo base_url().'index.php/home/wehooks';?>">
                        <div class="form-group">
                            <label for="object">Object</label>
                            <select class="form-control" id="object" name="object" aria-describedby="objectHelp">
                                <option value=''>Choose Object</option>
                                <?php foreach(['user','page'] as $object){
                                    $selected = ($editing['object'] == $object) ? 'selected' : '';
                                    echo "<option value='{$object}' {$selected}>{$object}</option>";
                                }?>
                            </select>
                            <small id="objectHelp" class="form-text text-muted"></small>
                        </div>
                        <div class="form-group">
                            <label for="callback">CallBack URL</label>
                            <input type="text" class="form-control" id="callback" name="callback" aria-describedby="callbackHelp" placeholder="Callback URL" value="<?php echo $editing['callback'];?>"/>
                            <small id="callbackHelp" class="form-text text-muted"></small>
                        </div>
                        <div class="form-group">
                            <label for="fields">Fields</label>
                            <input type="text" class="form-control" id="fields" name="fields" aria-describedby="objectHelp" placeholder="Enter comma seprated list of fields" value="<?php echo $editing['fields'];?>"/>
                            <small id="objectHelp" class="form-text text-muted"></small>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Object Name</th>
                            <th scope="col">Status</th>
                            <th scope="col">Callback URL</th>
                            <th scope="col">Fields</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($subscriptions as $k=>$sub){    
                            $sr_no = $k+1;
                            $status = ($sub['active']) ? 'Active' : 'Inactive';

                            $object_url = base_url() . "index.php/home/remove_subscription/{$sub['object']}/";                           

                            $fieldArr = [];
                            $fields = implode(",", array_map(function($val) use($object_url, &$fieldArr) {

                                $url = $object_url . $val['name'];
                                $fieldArr[] = $val['name'];
                                return "<div class='dropdown'>
                                            <button class='btn btn-primary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                                {$val['name']}
                                            </button>
                                            <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                                                <a class='dropdown-item' href='{$object_url}'>Remove All</a>
                                                <a class='dropdown-item' href='{$url}'>Remove Fields</a>
                                            </div>
                                        </div>";
                            },$sub['fields']));

                            $fieldStr = implode(",",$fieldArr);
                            $editUrl = base_url() . "index.php/home/wehooks_edit";

                            echo "<tr>
                                    <td>{$sr_no}</td>
                                    <td>{$sub['object']}</td>
                                    <td>{$status}</td>
                                    <td>{$sub['callback_url']}</td>
                                    <td>
                                        <form method='post' action='{$editUrl}'>
                                            <input type='hidden' name='object' value='{$sub['object']}'/>
                                            <input type='hidden' name='callback_url' value='{$sub['callback_url']}'/>
                                            <input type='hidden' name='fields' value='{$fieldStr}'/>
                                            <button type='submit' class='btn btn-success'>Edit</button>
                                        </form>
                                        {$fields}
                                    </td>
                                </tr>";
                        }?>
                    </tbody>
                </table>
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