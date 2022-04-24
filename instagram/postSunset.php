<?php
    require('../secrets.php');
    require('./utilities.php');

    if ($_GET['password'] === $SECRETS['UPLOAD_PASSWORD']) {
        $urlBase = $SECRETS['PUBLISH_SERVER_URL'].'history/sunsets/';

        header('Access-Control-Allow-Origin: *');
        header('Content-type: application/json');

        try {
            $videoUrl = $urlBase.$_GET['date'].'.mp4';

            $container = generateInstagramContainer(
                'video',
                $videoUrl,
                'testsunset'
            );
    
            sleep(30); // Wait for media to be ready for publishing

            publishInstagramContainer($container);

            echo json_encode([
                'success' => true,
                'videoUrl' => $videoUrl
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