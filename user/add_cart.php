<?php
    require_once ("../settings.php");
    require_once(__ROOT__.'/database.php');
    session_start();
    if (count($_SESSION) == 0) {
        header('location: ../index.php');
    }
    add_to_order($db, $_GET['mid']);
    $head = "location: detail.php?id=".$_GET['rid'];
    header($head);