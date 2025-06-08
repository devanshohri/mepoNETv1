<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

    <?php wp_head(); ?>
</head>
<body>

<header>
    <div class="header-container">

        <div class="header-logo">
            <div class="logo"> <a href="<?php echo site_url() ?>"><h1>MEPO<span>.network</h1></span></a></div>
        </div>

        <div class="header-buttons">
            <div class="header-search">
                <span class="material-icons">search</span>
            </div>
            
            <div class="header-add">
                <span class="material-icons">add</span>
            </div>
            
        </div>
        <div class="header-add-overlay">
            <div class="header-add-buttons">
                <div><a href="<?php echo site_url('/add-post'); ?>">Add Post<span class="material-icons">add</span></a></div>
                <div><a href="<?php echo site_url('/add-project-post'); ?>">Add Project<span class="material-icons">extension</span></a></div>
                <div><a href="<?php echo site_url('/add-event-post'); ?>">Add Event<span class="material-icons">calendar_today</span></a></div>
                <div class="icon-button" id="header-add-close"><span class="material-icons">close</span></div>
            </div>
        </div>
    </div>

    

</header>