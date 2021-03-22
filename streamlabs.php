<?php


class HTTP{

    private $statusCode,$body;
    public function statusCode(){
        return $this->statusCode;
    }
    public function body(){
        return $this->body;
    }
    public function set_statusCode($statusCode){
        $this->statusCode = $statusCode;
    }
    public function set_body($body){
        $this->body = $body;
    }

}

class Request{
    public function get($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Safari/537.36 OPR/71.0.3770.456');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,1);
  
        curl_setopt($ch,CURLOPT_AUTOREFERER,1);
 
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $http = new Http;
        $http->set_body($data);
        $http->set_statusCode($httpcode);
        curl_close($ch);
        return $http;
    }

    public function post($url,$data){
        $postData = '';
        foreach($data as $k => $v) { 
            $postData .= $k . '='.$v.'&'; 
        } 
        rtrim($postData, '&'); 
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_POST,count($postData));
        curl_setopt($ch,CURLOPT_POSTFIELDS,$postData);
       
        curl_setopt($ch,CURLOPT_AUTOREFERER,1);
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $http = new Http;
        $http->set_body($data);
        $http->set_statusCode($httpcode);

        return $http;
    }

}

class StreamLabs extends Request{

    private $api = "https://streamlabs.com/api/v1.0";

    private $client_id,$secret_id,$callback_url;
    public function __construct($data){
        $this->client_id = $data['client_id'];
        $this->secret_id = $data['secret_id'];
        $this->callback_url = $data['callback_url'];
    }

    public function authorize($scope){
        
        $url = $this->api . '/authorize?';
        $params = [
            "response_type" => "code",
            "client_id" => $this->client_id,
            "scope" => $scope,
            "redirect_uri" => $this->callback_url,
        ];
        return $url . str_replace('%2B','+',http_build_query($params, '', '&amp;'));
    }
    public function user($access_token){
        $url = $this->api . '/user?access_token='.$access_token;
        
        $response = $this->get($url);
        
        return $this->response($response);
    }
    public function donations($access_token){
        $url = $this->api . '/donations?access_token='.$access_token;
        $response = $this->get($url);
       return $this->response($response);
    }
    public function donate($data){
        $url = $this->api . '/donations';
        $post = [
            "name" => $data['name'],
            "message" => $data['message'],
            "identifier" => $data['identifier'],
            "amount" => $data['amount'],
            "currency" => isset($data['currency']) ? $data['currency'] : 'TRY',
            "access_token" => $data['access_token']
        ];
        $response = $this->post($url,$post);

        return $this->response($response);
    }
    public function token($code = '', $grant_type = 'authorization_code'){
        $url = $this->api . '/token';
        $data = [
            'grant_type'    => $grant_type,
            'client_id'     => $this->client_id,
            'client_secret' => $this->secret_id,
            'redirect_uri'  => $this->callback_url,
            'code'          => $code
        ];
        

        $response = $this->post($url,$data);

        return $this->response($response);
        
    }
    private function response($response){
        if($response->statusCode() == 200):
            return json_decode($response->body());
        endif;
        return null;
    }


}
