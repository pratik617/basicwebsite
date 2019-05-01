<?php 
namespace App\Helper;

use Illuminate\Database\Eloquent\Helper;
use App\Helper\Exceptions;

class DistanceHelper
{
    public static function distance($lat1, $lon1, $lat2, $lon2, $unit) 
    {
      try {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
          return 0;
        } else {
          $theta = $lon1 - $lon2;
          $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
          $dist = acos($dist);
          $dist = rad2deg($dist);
          $miles = $dist * 60 * 1.1515;
          $unit = strtoupper($unit);

          if ($unit == "KM") {
            return ($miles * 1.609344);
          } else if ($unit == "MILES") {
            return ($miles * 0.8684);
          } else {
            return $miles;
          }
        }
      } catch (Exception $e) {
        Exceptions::exception($e);  
      }
    }
    public static function drivingDistance($lat1, $long1, $lat2, $long2 ,$units="KM",$friend_latitude="",$friend_longitude="")
    {
        try {
          $url = "";
          if($units=="KM"){
            if($friend_latitude!="" && $friend_longitude!=""){
              $url = "https://maps.googleapis.com/maps/api/directions/json?origin="
                    .$lat1.",".$long1.
                    "&destination="
                    .$lat2.",".$long2.
                    "&units=metric&waypoints=via:".$friend_latitude.",".$friend_longitude."&key="
                    .config('constants.map_key');
            } else{
              $url = "https://maps.googleapis.com/maps/api/directions/json?origin="
                      .$lat1.",".$long1.
                      "&destination="
                      .$lat2.",".$long2.
                      "&units=metric&key="
                      .config('constants.map_key');
            }

          } else if($units=="MILES"){
            if($friend_latitude!="" && $friend_longitude!=""){
              $url = "https://maps.googleapis.com/maps/api/directions/json?origin="
                      .$lat1.",".$long1.
                      "&destination="
                      .$lat2.",".$long2.
                      "&units=metric&waypoints=via:".$friend_latitude.",".$friend_longitude."&key="
                      .config('constants.map_key');
            } else{
              $url = "https://maps.googleapis.com/maps/api/directions/json?origin="
                      .$lat1.",".$long1.
                      "&destination="
                      .$lat2.",".$long2.
                      "&units=imperial&key="
                      .config('constants.map_key');
            }
          }
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
          curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
          $response = curl_exec($ch);
          curl_close($ch);
          $response_a = json_decode($response, true);
          if($response_a["status"]=="REQUEST_DENIED"){
            DistanceHelper::drivingDistance($lat1, $long1, $lat2, $long2 ,$units);
          } else{
            return $response_a;
          }
        } catch (Exception $e) {
          Exceptions::exception($e);
        }
    }
}