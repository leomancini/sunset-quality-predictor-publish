<?php
    require('../secrets.php');

    if ($_POST['password'] === $SECRETS['UPLOAD_PASSWORD']) {
        $urlBase = $SECRETS['PUBLISH_SERVER_URL'].'instagram/images/';

        header('Access-Control-Allow-Origin: *');
        header('Content-type: application/json');
        
        function generateInstagramContainer($imageUrl, $caption) {
            global $SECRETS;

            $curl = curl_init('https://graph.facebook.com/v13.0/'.$SECRETS['FACEBOOK_APP_ID'].'/media?image_url='.$imageUrl.'&caption='.urlencode($caption).'&access_token='.$SECRETS['FACEBOOK_ACCESS_TOKEN']);

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLINFO_HEADER_OUT, true);
            curl_setopt($curl, CURLOPT_POST, true);

            $responseData = curl_exec($curl);

            curl_close($curl);

            $response = json_decode($responseData, true);

            return $response['id'];
        }

        function publishInstagramContainer($container) {
            global $SECRETS;

            $curl = curl_init('https://graph.facebook.com/v13.0/'.$SECRETS['FACEBOOK_APP_ID'].'/media_publish?creation_id='.$container.'&access_token='.$SECRETS['FACEBOOK_ACCESS_TOKEN']);

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLINFO_HEADER_OUT, true);
            curl_setopt($curl, CURLOPT_POST, true);

            $responseData = curl_exec($curl);

            curl_close($curl);
        }

        function uploadImage() {
            global $urlBase;

            $directory = './images/';
            $filename = time().'.png';
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
                'errpr' => $error
            ]);
        }
    } else {
        http_response_code(403);
        die('Forbidden');
    }
?>