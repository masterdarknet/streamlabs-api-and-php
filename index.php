<?php

require "streamlabs.php";



$streamlabs = new StreamLabs(array(
        "client_id" => "CLIENT_ID_BURAYA_GELECEKTIR",
        "secret_id" => "CLIENT_SECRET_BURAYA_GELECEKTIR",
        "callback_url" => "https://education.masterdark.net/callback.php"
    ));

$link = $streamlabs->authorize("donations.read+donations.create");

?>



<!DOCTYPE html>
<html>
<head>
<title>StremLabs Tutorial</title>

<style>
    *{padding:0;margin:0;box-sizing:border-box;text-decoration:none;}
    body{
        width:100vw;
        height:100vh;
        background-color:#1b1d1b;
        color:#ffc;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    a{
        color:#fff;
        font-family:Arial;
        font-size:16px;
    }
    a:not(:last-child):after{
        content:'|';
        padding:0 6px;
    }
    
</style>
</head>

<body>


<a href="<?=$link?>" target="_blank">Streamlabs'a bağlan</a>

<a href="donate_gonder.php">Bağış gönder</a>

<a href="donate_listele.php">Donate listele</a>


</body>
</html>
