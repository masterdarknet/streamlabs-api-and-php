<?php


session_start();

require "streamlabs.php";

$streamlabs = new StreamLabs(array(
        "client_id" => "SMqJaq38C2tCJZ8cnFRqwyJEPlpFo2PMbVqT7XCM",
        "secret_id" => "CLIENT_SECRET_BURAYA",
        "callback_url" => "https://education.masterdark.net/callback.php"
    ));


if($_SERVER["REQUEST_METHOD"] == "POST"):
    
    $username = $_POST['donate_username'];
    
    $list = $streamlabs->donations($_SESSION['streamlabs'][$username]['authorize']->access_token);
 
endif;


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
        flex-direction:column;
    }
    
    table tr td{
      padding:6px 12px;   
    }
    
    .alert{padding:12px 16px};
    .alert.success{background-color: darkgreen;}
    .alert.danger{background:tomato;color:#ffd;}
    
</style>
</head>

<body>
<form method="post">
    
    <tr>
        <td>Bağış listenecek kullanıcı adı</td>
        <td> <?php if(count($_SESSION['streamlabs']) > 0): ?>
            <select name="donate_username">
            <?php foreach($_SESSION['streamlabs'] as $user => $info): ?>
                <option name="<?=$user?>"><?=$user?></option>
            <?php endforeach ?>
            </select>
           <?php else: ?>
            <p>Kayıt kullanıcı bulunmuyor.</p>
            <?php endif ?>
        </td>
    </tr>
    
    <tr>
        <td><button type="submit" name="listele">BAĞIŞLARI GÖRÜNTÜLE</button></td>
        
    </tr>
    
</form>


<table>
    <thead>
        <tr>
            <th>Gönderim Zamanı</th>
            <th>İsim</th>
            <th>Mesaj</th>
            <th>Miktar</th>
            <th>Para Birim</th>
        </tr>
    </thead>
    <?php if(property_exists($list,'data')): ?>
    <tbody>
        <?php foreach($list->data as $donate): ?>
        <tr>
            <td><?=date('D-M-Y H:i:s',$donate->created_at)?></td>
            <td><?=$donate->name?></td>
            <td><?=$donate->message?></td>
            <td><?=number_format($donate->amount,2)?></td>
            <td><?=$donate->currency?></td>
        </tr>
        
        <?php endforeach ?>
    </tbody>
    
    <?php else : ?>
    
    <p>Bağış bulunmuyor.</p>
    <?php endif ?>
    
    
</table>

</body>

</html>
