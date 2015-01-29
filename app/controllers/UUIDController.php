<?php

class UUIDController extends BaseController {

	public function uuid($name) {
	    $player = $this->getPlayerByName($name);
	    $response = Response::make($player->uuid);
        $response->header('Content-Type', 'text/plain');
        return $response;
	}
    
    public function name($uuid) {
        $player = $this->getPlayerById($uuid);
        $response = Response::make($player->name);
        $response->header('Content-Type', 'text/plain');
        return $response;
    }
    
    public function getPlayerByName($name) {
        $count = Player::where('name', $name)->get();
        if($count->count() > 1){
            $count->each(function ($player){
                $player->delete(); // There can only be one
            });
        }
        
        $player = Player::where('name', $name)->first();
        
        if($player != null && $player->isExpired()) { //Its expired so we must fetch more
            $player->delete();
            $player = null;
        }
        
        if ($player == null) {
            $player = new Player();
            $uuid = $this->fetchUuid($name);
            $player->name = $name;
            $player->uuid = $uuid;
            
            $player->expires = time() + (60 * 60 * 12); //12 hours
            $player->save();
        }
        
        return $player;
    }
    
    public function getPlayerById($uuid) {
        $count = Player::where('uuid', $uuid)->get();
        if($count->count() > 1){
            $count->each(function ($player){
                $player->delete(); // There can only be one
            });
        }
        
        $player = Player::where('uuid', $uuid)->first();
        
        if($player != null && $player->isExpired()) { //Its expired so we must fetch more
            $player->delete();
            $player = null;
        }
        
        if ($player == null) {
            $player = new Player();
            $name = $this->fetchName($uuid);
            $player->name = $name;
            $player->uuid = $uuid;
            
            $player->expires = time() + (60 * 60 * 12); //12 hours
            $player->save();
        }
        
        return $player;
    }
    
    public function fetchName($uuid) {
        $ch = curl_init();
        $curlConfig = array(
            CURLOPT_URL => "http://uuid.turt2live.com/api/v2/name/" . $uuid,
            CURLOPT_RETURNTRANSFER => true,
        );
        curl_setopt_array($ch, $curlConfig);
        $rawResponse = curl_exec($ch);
        curl_close($ch);
        
        $json = json_decode($rawResponse, true);
        return $json["name"];
    }
    
    public function fetchUuid($name) {
        $ch = curl_init();
        $curlConfig = array(
            CURLOPT_URL => "http://uuid.turt2live.com/api/v2/uuid/" . $name,
            CURLOPT_RETURNTRANSFER => true,
        );
        curl_setopt_array($ch, $curlConfig);
        $rawResponse = curl_exec($ch);
        curl_close($ch);
        
        $json = json_decode($rawResponse, true);
        return $json["uuid"];
    }
}
