<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\WhatsappController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/ultra',[WhatsappController::class, 'index'])->name('ultra');
Route::post('/pesan-masuk',[WhatsappController::class, 'webhook'])->name('post-webhook');
Route::get('/pesan-masuk',[WhatsappController::class, 'webhook'])->name('get-webhook');
Route::get('/message', function() {
    // show a form
    return view('message');
});

Route::post('/message', function(Request $request) {
    // TODO: validate incoming params first!
    $NEXMO_API_KEY = '5d1a459c';
    $NEXMO_API_SECRET = 'WhvXkp6TDDZfoKwN';
    $headers = ["Authorization" => "Basic " . base64_encode($NEXMO_API_KEY . ":" . $NEXMO_API_SECRET)];
    $url = "https://messages-sandbox.nexmo.com/v1/messages";
    $to = $request->input('number');
    //$to = "6287864496339";
    $params = [
        "from" => "14157386102",
        "to" => $to,
        "message_type" => "text",
        "text" => "This is a WhatsApp Message sent from the Messages API",
        "channel" => "whatsapp"
        /*
        "to" => [
            "type" => "whatsapp", 
            "number" => $request->input('number')
        ],
        "from" => [
            "type" => "whatsapp", 
            "number" => "14157386102"
        ],
        "message" => [
            "content" => [
                "type" => "text",
                "text" => "Hello from Vonage and Laravel :) Please reply to this message with a number between 1 and 100"
            ]
        ]*/
    ];
    $client = new \GuzzleHttp\Client();
    $response = $client->request('POST', $url, ["headers" => $headers, "json" => $params]);
    $data = $response->getBody();
    Log::Info($data);

    return view('thanks');
});

Route::post('/webhooks/status', function(Request $request) {
    $data = $request->all();
    Log::Info($data);
});

Route::post('/webhooks/inbound', function(Request $request) {
    $NEXMO_API_KEY = '5d1a459c';
    $NEXMO_API_SECRET = 'WhvXkp6TDDZfoKwN';
    $data = $request->all();
    $text = $data['message']['content']['text'];
    $number = intval($text);
    Log::Info($number);
    Log::Info($data);
    if($number > 0) {
        $random = rand(1, 8);
        Log::Info($random);
        $respond_number = $number * $random;
        Log::Info($respond_number);
        $url = "https://messages-sandbox.nexmo.com/v1/messages";
        /*$params = ["to" => ["type" => "whatsapp", "number" => $data['from']['number']],
            "from" => ["type" => "whatsapp", "number" => "14157386102"],
            "message" => [
                "content" => [
                    "type" => "text",
                    "text" => "The answer is " . $respond_number . ", we multiplied by " . $random . "."
                ]
            ]
        ];*/
        $params = [
            "from" => "14157386102",
            "to" => "6287864496339",
            "message_type" => "text",
            "text" => "The answer is " . $respond_number . ", we multiplied by " . $random . ".",
            "channel" => "whatsapp"
        ];
        $headers = ["Authorization" => "Basic " . base64_encode($NEXMO_API_KEY . ":" . $NEXMO_API_SECRET)];

        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', $url, ["headers" => $headers, "json" => $params]);
        $data = $response->getBody();
    }
    Log::Info($data);
});
