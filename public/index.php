<?php
require __DIR__ . '/../vendor/autoload.php';
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use \LINE\LINEBot;
use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
use \LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use \LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use \LINE\LINEBot\SignatureValidator as SignatureValidator;
$pass_signature = true;
// set LINE channel_access_token and channel_secret
$channel_access_token = "eusoQ3P6jWN41mpwoRXTS8B7/jlREJlMpH/OjkPM1+On3kbV8MpBWROWTE6jSBA00Z7FmNxusWCGBuwcvz69uCISpJCXkFr5eyzfLYuJ4vmaO6DWqZVG3DVzfMEyzMeKtSPgRyHNR6xGk9HXmC+UoQdB04t89/1O/w1cDnyilFU=";
$channel_secret = "debe386331f45b67423bb962e044f038";
// inisiasi objek bot
$httpClient = new CurlHTTPClient($channel_access_token);
$bot = new LINEBot($httpClient, ['channelSecret' => $channel_secret]);
$app = AppFactory::create();
$app->setBasePath("/public");
$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello World!");
    return $response;
});
// buat route untuk webhook
$app->post('/webhook', function (Request $request, Response $response) use ($channel_secret, $bot, $httpClient, $pass_signature) {
// get request body and line signature header
    $body = $request->getBody();
    $signature = $request->getHeaderLine('HTTP_X_LINE_SIGNATURE');
    // log body and signature
    file_put_contents('php://stderr', 'Body: ' . $body);
    if ($pass_signature === false) {
    // is LINE_SIGNATURE exists in request header?
        if (empty($signature)) {
        return $response->withStatus(400, 'Signature not set');
        }
    // is this request comes from LINE?
        if (!SignatureValidator::validateSignature($body, $channel_secret, $signature))
        {
            return $response->withStatus(400, 'Invalid signature');
        }
    }
// kode aplikasi nanti disini
});
$app->run();