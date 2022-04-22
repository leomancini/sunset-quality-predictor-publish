<?php
    require('../secrets.php');

    if ($_POST['password'] === $SECRETS['UPLOAD_PASSWORD']) {
        $urlBase = 'http://skyline.noshado.ws/publish/history/predictions/';

        header('Access-Control-Allow-Origin: *');
        header('Content-type: application/json');

        function savePrediction() {
            global $urlBase;

            $directory = './predictions/';
            $filename = time().'.json';
            $file = $directory.$filename;

            $data = $_POST['data'];
            
            file_put_contents($file, $data);

            $url = $urlBase.$filename;

            return $url;
        }

        try {
            $imageUrl = savePrediction();

            echo json_encode([
                'success' => true,
                'imageUrl' => $imageUrl
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