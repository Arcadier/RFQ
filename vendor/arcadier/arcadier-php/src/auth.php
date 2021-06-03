<?php
    // namespace Arcadier;
    require dirname(__FILE__,4) . "/autoload.php";

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    function getAdminToken(){
    
        $url = $_ENV['PROTOCOL'].'://'.$_ENV['DOMAIN'] . '/token';
        $body = 'grant_type=client_credentials&client_id=' . $_ENV['CLIENT_ID'] . '&client_secret=' . $_ENV['CLIENT_SECRET'] . '&scope=admin';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);
    
        return json_decode($result, true);
        
    }

    function getMarketplaceDomain(){
        return $_ENV['DOMAIN'];
    }

    function getProtocol(){
        return $_ENV['PROTOCOL'];
    }
?>