<?php

$timestamp=time();
$amp_story_url = 'www.mydomain.in/amp/story_alias';
$ampBaseUrl = "https://www-mydomain-in.cdn.ampproject.org";
$signatureUrl = '/update-cache/c/s/'.$amp_story_url.'?amp_action=flush&amp_ts='.$timestamp;

// opening the private key
$pkeyid = openssl_pkey_get_private("file://private-key.pem");

 // generating the signature
openssl_sign($data, $signature, $pkeyid, OPENSSL_ALGO_SHA256);
openssl_free_key($pkeyid);

// urlsafe base64 encoding
$signature = urlsafe_b64encode($signature);
// final url for updating
$ampUrl = $ampBaseUrl . $signatureUrl."&amp_url_signature=".$signature;

// clear amp cache
$ch = curl_init($ampUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$data = curl_exec($ch);
curl_close($ch);
print($data);

function urlsafe_b64encode($string) {
    return str_replace(array('+','/','='),array('-','_',''), base64_encode($string));
}
