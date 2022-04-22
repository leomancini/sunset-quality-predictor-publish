<?php
    require('../secrets.php');

    if ($_POST['password'] === $SECRETS['UPLOAD_PASSWORD']) {
        $urlBase = 'http://skyline.noshado.ws/publish/history/predictions/';

        header('Access-Control-Allow-Origin: *');
        header('Content-type: application/json');

        function savePrediction() {
            global $urlBase;

            $predictionData = [
                'date' => $_POST['date'],
                'rating' => $_POST['rating'],
                'confidence' => $_POST['confidence']
            ];

            $directory = './predictions/';
            $filename = $predictionData['date'].'.json';
            $file = $directory.$filename;
            
            file_put_contents($file, json_encode($predictionData));

            $url = $urlBase.$filename;

            return $url;
        }

        try {
            $predictionHistoryUrl = savePrediction();

            echo json_encode([
                'success' => true,
                'predictionHistoryUrl' => $predictionHistoryUrl
            ]);
        } catch (exception $error) {
            echo json_encode([
                'success' => false,
                'error' => $error
            ]);
        }
    } else {
        http_response_code(403);
        die('Forbidden');
    }
?>