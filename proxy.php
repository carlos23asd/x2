
<?php
// proxy.php: Reenvía .m3u8 y .ts evitando bloqueos por CORS y User-Agent

if (!isset($_GET['url'])) {
    http_response_code(400);
    echo "URL missing.";
    exit;
}

$url = $_GET['url'];
$headers = [
    "User-Agent: VLC/3.0.0 LibVLC/3.0.0",
    "Referer: " . $url,
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_HEADER, false);

$data = curl_exec($ch);
$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
curl_close($ch);

if ($data === false) {
    http_response_code(502);
    echo "Error al obtener el recurso.";
    exit;
}

header("Access-Control-Allow-Origin: *");
header("Content-Type: " . $contentType);
echo $data;
?>
