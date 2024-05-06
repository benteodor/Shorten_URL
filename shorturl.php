<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="style.css">

<head>
    <meta charset="UTF-8">
    <title>URL Shortener</title>
</head>

<body>
    <form method="post">
        <label for="longUrl">Enter URL to shorten:</label>
        <input type="text" id="longUrl" name="longUrl" required>
        <button type="submit">Shorten URL</button>
    </form>
</body>

</html>

<?php

function shortenURL($longUrl, $accessToken) {
    $url = 'https://unelma.io/api/v1/link';
    $data = [
        "type" => "direct",
        "password" => null,
        "active" => true,
        "expires_at" => "2024-05-06",
        "activates_at" => "2024-03-25",
        "utm" => "utm_source=google&utm_medium=banner",
        "domain_id" => null,
        "long_url" => $longUrl
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'accept: application/json',
        'Content-Type: application/json',
        'Authorization: Bearer ' . $accessToken,
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        return 'Error:' . curl_error($ch);
    } else {
        $responseDecoded = json_decode($response, true);
        if (isset($responseDecoded['link']['short_url'])) {
            return $responseDecoded['link']['short_url'];
        } else {
            return 'Shortened URL not found in the response.';
        }
    }

    curl_close($ch);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accessToken = '16|Luvo9pvtllzyPq2sojoeU3uz8gH6omV13Js1ah2s26e54053'; 

    $longUrl = $_POST['longUrl'];
    $shortUrl = shortenURL($longUrl, $accessToken);

    echo 'Here is the shortened URL: <a href="' . $shortUrl . '">' . $shortUrl . '</a>';
}

?>


