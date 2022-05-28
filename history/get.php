<?php
    function getHistorialPrediction($date) {
	global $SECRETS;
        $urlBase = $SECRETS['PUBLISH_SERVER_URL'].'history/predictions/';

        $request = curl_init();
        curl_setopt($request, CURLOPT_URL, $SECRETS['PUBLISH_SERVER_URL'].'history/predictions/'.$date->format('Y-m-d').'.json');
        curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
        $results = curl_exec($request);
        curl_close($request);

        return json_decode($results);
    }
?>
