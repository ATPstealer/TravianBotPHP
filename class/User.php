<?php
/**
 * Class User 
 * for everything
 */


class User{

    function login() {
        global $BASE_URL, $NAME, $PASSWORD;
        $time = time();
        $ch = curl_init();
        $url = $BASE_URL.'login.php';
        curl_setopt($ch, CURLOPT_URL, $url ); 
        curl_setopt($ch, CURLOPT_HEADER, 0); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, 1); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
            'name'=>$NAME,
            'password'=>$PASSWORD,
            's1'=>'Войти',
            'w'=>'1920:1080',
            'login'=>$time
        ));
        curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie/cookie.txt'); 
        curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie/cookie.txt');
        $data = curl_exec($ch);
        curl_close($ch);
        return (strpos($data,"Обзор героя"))?true:false;
        ; 
    }

    function ifLogin() {
        global $BASE_URL;
        $work = curl_init();
        curl_setopt($work, CURLOPT_URL, $BASE_URL."dorf1.php"); 
        curl_setopt($work, CURLOPT_HEADER, 0); // 
        curl_setopt($work, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($work, CURLOPT_FOLLOWLOCATION, 1); 
        curl_setopt($work, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($work, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($work, CURLOPT_COOKIEJAR, 'cookie/cookie.txt'); 
        curl_setopt($work, CURLOPT_COOKIEFILE,  'cookie/cookie.txt');
        $data = curl_exec($work);
        curl_close($work);
        return (strpos($data,"Обзор героя"))?true:false;
    }

    function read($url) {
        global $BASE_URL;
        $url = $BASE_URL.$url;
        $work = curl_init();
        curl_setopt($work, CURLOPT_URL, $url );
        curl_setopt($work, CURLOPT_HEADER, 0);
        curl_setopt($work, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($work, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($work, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($work, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($work, CURLOPT_COOKIEJAR, 'cookie/cookie.txt');
        curl_setopt($work, CURLOPT_COOKIEFILE,  'cookie/cookie.txt');
        return  curl_exec($work);
    }

    function parseToHTML($url) {
        return $html = str_get_html(User::read($url));
    }

    function getVillages() {
        $html = User::parseToHTML("dorf1.php");
        $i = 1;
        foreach($html->find("a") as $a){
            if (strpos($a->href, "newdid") && !strpos($a->href, "tt"))
            {
                $villages[$i++] = $a->href;
            }
        }
        return $villages;
    }

    function showVillage($village){
        return User::parseToHTML("dorf1.php".$village);
    }

    function showVillageCenter($village){
        return User::parseToHTML("dorf2.php".$village);
    }

}
