<?php
    require('../secrets.php');
    require('./utilities.php');

    if ($_POST['password'] === $SECRETS['UPLOAD_PASSWORD']) {
        $urlBase = $SECRETS['PUBLISH_SERVER_URL'].'instagram/images/';

        header('Access-Control-Allow-Origin: *');
        header('Content-type: application/json');

        function uploadImage() {
            global $urlBase;

            $directory = './images/';
            $filename = $_POST['date'].'.png';
            $file = $directory.$filename;

            $image = $_POST['imageData'];
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);

            $data = base64_decode($image);
            
            file_put_contents($file, $data);

            $url = $urlBase.$filename;

            return $url;
        }

        try {
            $imageUrl = uploadImage();

            $container = generateInstagramContainer(
                'image',
                $imageUrl,
                $_POST['caption']
            );

            publishInstagramContainer($container);

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
        http_response_code(404);
        die('File not found.');
    }
?>