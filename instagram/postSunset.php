<?php
    require('../config.php');
    require('../functions/getSunsetTime.php');
    require('./utilities.php');

    if ($_GET['password'] === $SECRETS['UPLOAD_PASSWORD']) {
        $urlBase = $SECRETS['PUBLISH_SERVER_URL'].'history/sunsets/';

        header('Access-Control-Allow-Origin: *');
        header('Content-type: application/json');

        try {
            $dateInput = $_GET['date'];
            $date = new DateTime($dateInput);

            $videoUrl = $urlBase.$dateInput.'.mp4';

            $sunsetTime = getSunsetTime($date);
            $sunsetTimeFormatted = $sunsetTime->format('g:i A');

            $captions = [
                'Timelapse of the sunset on '.$date->format('l, F j, Y').' at '.$sunsetTimeFormatted,
                'Timelapse '.$sunsetTimeFormatted
            ];

            $caption = $captions[array_rand($captions)];

            echo $caption;

            // $container = generateInstagramContainer(
            //     'video',
            //     $videoUrl,
            //     $caption
            // );
    
            // sleep(30); // Wait for media to be ready for publishing

            // publishInstagramContainer($container);

            // echo json_encode([
            //     'success' => true,
            //     'videoUrl' => $videoUrl
            // ]);
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