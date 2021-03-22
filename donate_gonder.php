<?php 

session_start();

require "streamlabs.php";

$streamlabs = new StreamLabs(array(
        "client_id" => "SMqJaq38C2tCJZ8cnFRqwyJEPlpFo2PMbVqT7XCM",
        "secret_id" => "SECRET_ID_BURAYA",
        "callback_url" => "https://education.masterdark.net/callback.php"
    ));



if($_SERVER["REQUEST_METHOD"] == "POST"):
    
    $donate_username = $_POST['donate_username'];
    $username = $_POST['username'];
    $amount = $_POST['amount'];
    $message = $_POST['message'];
    
    $donate = [
        'name' => $username,
        'identifier' => $username,
        'message' => $message,
        'amount' => $amount,
        'currency' =>'TRY' ,
        'access_token' => $_SESSION['streamlabs'][$donate_username]['authorize']->access_token
    ];
    
    $result = $streamlabs->donate($donate);
    
    
    if($result):
        $bagis_gonderildi = true;
    else:
        $bagis_gonderildi = false;
    endif;
        
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

<?php if(isset($bagis_gonderildi)): ?>
    
    <?php if($bagis_gonderildi): ?>
    
        <div class="alert success"><?=$donate_username?> kullanıcısına <?=$username?> olarak <?=$amount?> <?=$donate['currency']?> bağış gönderdiniz.</div>
    <?php else:?>
        <div class="alert danger">Bağış gönderilemedi.</div>
    <?php endif ?>

<?php endif ?>

<form method="post">
    
   
    
    <table>
    <tr>
        <td>Donate gönderilecek kullanıcı adı:</td>
        <td>
             <?php if(count($_SESSION['streamlabs']) > 0): ?>
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
        <td>Kullanıcı adınız:</td>
         <td><input name="username" /></td>
     </tr>
    <tr>
         <td>Mesajınız</td>
         <td><textarea name="message"></textarea></td>
    </tr>
     <tr>
         <td>Bağış miktarı</td>
         <td><input type="number" name="amount" /></td>
    </tr>
    

    
    
     <tr><td><button type="submit" name="gonder">BAĞIŞ GÖNDER</button></td></tr>
    </table>
    
    
</form>

</body>

</html>
