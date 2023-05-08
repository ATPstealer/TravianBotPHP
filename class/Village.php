<?php

class Village{

    public $villageUrl;
    public $htmlResources;
    public $resourcesUrl;
    public $resources;
    public $htmlCenter;
    public $building;
    public $name; 

    function __construct($village) {
        $this->htmlResources = User::showVillage($village);
        $this->villageUrl = $village;
        $this->getResources();
        $this->htmlCenter = User::showVillageCenter($village);
        $this->getCenter();
        $this->name = $this->htmlResources->getElementById("villageNameField")->text();
    }

    function getResources() { 
        $i = 1;
        foreach($this->htmlResources->find("area") as $area){
            if (strpos($area->href, "id"))
            {
                $this->resourcesUrl[$i] = $area->href;
                $this->resources[$i++] = $area->alt;
            }
        }
        
    }

    function getCenter() {

        for ($i = 19; $i <= 39; $i++){
             // Узнаем что за здание    
            foreach ($this->htmlCenter->find("div.buildingSlot")[$i] as $c ){
                if (is_array($c) && isset($c["class"])){
                    $find = explode(" ",$c["class"]);
                    foreach ($find as $g){
                        if ( (strpos($g,"g") !== false) && (strpos($g,"buildingSlot") === false))
                        {
                            $buildingName = $g;
                        }
                    }
                }
            }

            if ( $buildingName === "g0"){ continue; }

            $this->building[$i] = new Building;
            $this->building[$i]->g = $buildingName;

             // Узнаем URL для билд, но оно и так по ID известно
            foreach ($this->htmlCenter->find("div.buildingSlot")[$i]->nodes[0] as $m ){
                if (is_array($m) && isset($m["onclick"])){
                    $u = explode("'",$m["onclick"]);
                    $this->building[$i]->buildingUrl = $u[1];
                    $l = explode("Уровень ",$m["title"]);
                    $l = explode("<",$l[1]);
                    $this->building[$i]->lvl = $l[0];
                }
            }
        }
    }

    function build($resUrl) {
        $buildHTML = User::parseToHTML($resUrl);
        $buttons = $buildHTML->find("button");

        foreach($buttons as $button){
            foreach ($button as $m){
                if (is_array($m) && isset($m["value"])){
                    if (strpos($m["value"],"лучшить до уровн")){
                        $buildUrl = explode("'",$m["onclick"]);
                        User::read($buildUrl[1]);
                        return 1;
                    }
                }
            }
        }
        return 0;
    }

    function findMinResources() {
        $minResources = 1;
        $minLvl = 21;

        for ($i = 1; $i <= 18; $i++) {
            $lvl = preg_replace("/[^0-9]/", '', $this->resources[$i]);
            if ($lvl < $minLvl){
                $minResources = $i;
                $minLvl = $lvl;
            }
        } 
        return $this->resourcesUrl[$minResources];
    }

    function startFestival() { // проводим праздники
        $cityHall = "";
        foreach ($this->building  as $build){ 
            if ( ($build->g == "g24" )) {
                $cityHall = $build->buildingUrl;
            }
        }
        User::read($cityHall."&a=1");
    }

    function findMinBuilding() {
        $minBuilding;
        $minLvl = 21;

        foreach ($this->building  as $build){ // Глав, амб, скл, акад, рынок до 20
            if (($build->g == "g10") || ($build->g == "g11") || ($build->g == "g15") ||
                ($build->g == "g22") || ($build->g == "g17") ){
                if ($build->lvl < $minLvl){
                    $minBuilding = $build->buildingUrl;
                    $minLvl = $build->lvl;
                }
            }
        }

        foreach ($this->building  as $build){ // ресовые здания до 5
            if ( ( ($build->g == "g9" ) || ($build->g == "g8" ) || ($build->g == "g7" ) 
            ||  ($build->g == "g6" ) || ($build->g == "g5" ) ) && ($build->lvl < 5) ) {
                if ($build->lvl < $minLvl){
                    $minBuilding = $build->buildingUrl;
                    $minLvl = $build->lvl;
                }
            }
        }

        foreach ($this->building  as $build){ // Реза и ратуша до 10
            if ( ( ($build->g == "g25" ) || ($build->g == "g24" ) ) && ($build->lvl < 10) ) {
                if ($build->lvl < $minLvl){
                    $minBuilding = $build->buildingUrl;
                    $minLvl = $build->lvl;
                }
            }
        }

        foreach ($this->building  as $build){ // Казарма до 3
            if ( ( ($build->g == "g19" ) ) && ($build->lvl < 3) ) {
                if ($build->lvl < $minLvl){
                    $minBuilding = $build->buildingUrl;
                    $minLvl = $build->lvl;
                }
            }
        }

        return $minBuilding;
    }


}