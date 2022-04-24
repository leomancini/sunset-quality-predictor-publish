<?php
    require('../secrets.php');

    function generateInstagramContainer($mediaType, $mediaUrl, $caption) {
        global $SECRETS;

        $url = 'https://graph.facebook.com/v13.0/'.$SECRETS['FACEBOOK_APP_ID'];

        if ($mediaType === 'image') {
            $url .= '/media?image_url='.$mediaUrl;
        } else if ($mediaType === 'video') {
            $url .= '/media?media_type=VIDEO&video_url='.$mediaUrl;
        }

        $url .= '&caption='.urlencode($caption).'&access_token='.$SECRETS['FACEBOOK_ACCESS_TOKEN'];

        $curl = curl_init($url);

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
?>