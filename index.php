<?php
include "class/User.php";
include "class/Village.php";
include "class/Building.php";
include "simpleHTML/simple_html_dom.php";
include "config.php";

$user = new User;

//sleep (rand(0,1));

if( !$user->ifLogin() ){
    if($user->login()) {
        echo "Залогинились\n";
    } else {
        echo "Не залогинились. Проверьте config.php\n";
    }
}

$villages = $user->getVillages(); // получаем массив ссылок на деревни



// Строим 
foreach ($villages as $vil){

    $village = new Village($vil); // Создаем объект деревни

    if ($BUILD_RESOURCES){ // Строим ресурсы
        if ( $village->build($village->findMinResources()) ){
            echo $village->name." Заказали ресовое поле\n";
        }
    }

    if ($BUILD_BUILDINGS){ // Строим здания
        if ( $village->build($village->findMinBuilding()) ){
            echo $village->name." Заказали здание\n";
        }
    }

    if ($TO_CELEBRATE){
        $village->startFestival() ;
    }

}
