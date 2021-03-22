

<?php

// eğer code yoksa sayfayı durdur.
if(!isset($_GET['code']))
    die;
    

$code = $_GET['code'];

require "streamlabs.php";

$streamlabs = new StreamLabs(array(
        "client_id" => "CLIENT_ID_BURAYA_GELECEKTIR",
        "secret_id" => "CLIENT_SECRET_BURAYA_GELECEKTIR",
        "callback_url" => "https://education.masterdark.net/callback.php"
    ));


$data = $streamlabs->token($code);

if($data):

    
    // kullanici bilgilerini getir
    $user = $streamlabs->user($data->access_token);
    
    // kullanici bilgilerini kaydet
    session_start();
    
    $twitch_name = $user->twitch->display_name;
    
    $save = [
        "authorize" => $data,
        "user" => $user,
        "twich" => $twitch_name
    ];
    
    $_SESSION['streamlabs'][$twitch_name] = $save;


    print 'Bağlantı başarılı, yönlendiriliyor..';
    
    header("Refresh:3; url=donate_gonder.php");

else:
    
    print 'bağlantı işlemi tamamlanamadı.';

endif;

?>
