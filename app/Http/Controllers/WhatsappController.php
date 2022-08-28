<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use UltraMsg\WhatsAppApi;
use App\ultramsgDictionary;

class WhatsappController extends Controller
{
    public function index(){
        $ultramsg_token="x2epn697nmzwnolg"; // Ultramsg.com token
        $instance_id="instance15890"; // Ultramsg.com instance id
        $client = new WhatsAppApi($ultramsg_token,$instance_id);

        $to="6285231444789"; 
        $body="Hello world"; 
        $api=$client->sendChatMessage($to,$body);
        dump($api);

        $image="https://file-example.s3-accelerate.amazonaws.com/images/test.jpg"; 
        $caption="image Caption"; 
        $priority=10;
        $referenceId="SDK";
        $nocache=false; 
        $api=$client->sendImageMessage($to,$image,$caption,$priority,$referenceId,$nocache);
        dump($api);

        $filename="image Caption"; 
        $document="https://file-example.s3-accelerate.amazonaws.com/documents/cv.pdf"; 
        $api=$client->sendDocumentMessage($to,$filename,$document);
        dump($api);

        $audio="https://file-example.s3-accelerate.amazonaws.com/audio/2.mp3"; 
        $api=$client->sendAudioMessage($to,$audio);
        dump($api);

        $audio="https://file-example.s3-accelerate.amazonaws.com/voice/oog_example.ogg"; 
        $api=$client->sendVoiceMessage($to,$audio);
        dump($api);

        $video="https://file-example.s3-accelerate.amazonaws.com/video/test.mp4";
        $caption="video Caption"; 
        $api=$client->sendVideoMessage($to,$video,$caption);
        dump($api);

        $link="https://ultramsg.com"; 
        $api=$client->sendLinkMessage($to,$link);
        dump($api);

        $contact="14000000001@c.us"; 
        $api=$client->sendContactMessage($to,$contact);
        dump($api);

        $address="ABC company \n Sixth floor , office 38"; 
        $lat="25.197197"; 
        $lng="55.2721877"; 
        $api=$client->sendLocationMessage($to,$address,$lat,$lng);
        dump($api);

        $vcard="BEGIN:VCARD
        VERSION:3.0
        N:lastname;firstname
        FN:firstname lastname
        TEL;TYPE=CELL;waid=14000000001:14000000002
        NICKNAME:nickname
        BDAY:01.01.1987
        X-GENDER:M
        NOTE:note
        ADR;TYPE=home
        ADR;TYPE=work
        END:VCARD";
        $vcard = preg_replace("/[\n\r]/", "\n", $vcard);
        $api=$client->sendVcardMessage($to,$vcard);
        dump($api);
        \Log::info($api);
    }
    public function webhook(Request $request){
        $ultramsg_token="x2epn697nmzwnolg"; // Ultramsg.com token
        $instance_id="instance15890"; // Ultramsg.com instance id
        $ultramsgDictionary = new ultramsgDictionary();
        $client = new WhatsAppApi($ultramsg_token,$instance_id);
        $decoded = $request->all();
        if (isset($decoded['data'])) {
            $message = $decoded['data'];
            $text = $this->convert($message['body']);

            // message shouldn't be send from your WhatsApp number, because it calls recursion
            if (!$message['fromMe']) {
                $to = $message['from'];
                $val = mb_strtolower($text, 'UTF-8');
                switch ($val) {
                    case in_array($val, $ultramsgDictionary->welcomeIntent()): {
                            $randMsg = $ultramsgDictionary->welcomeResponses();
                            //$client->sendChatMessage($to, $randMsg);
                            break;
                        }
                    case '1': {
                            $client->sendChatMessage($to, date('d.m.Y H:i:s'));
                            break;
                        }
                    case '2': {
                            $image="https://file-example.s3-accelerate.amazonaws.com/images/test.jpg"; 
                            $caption="image Caption"; 
                            $priority=10;
                            $referenceId="SDK";
                            $nocache=false; 
                            $client->sendImageMessage($to,$image,$caption,$priority,$referenceId,$nocache);
                            break;
                        }
                    case '3': {
                            $client->sendDocumentMessage($to, "cv.pdf", "https://file-example.s3-accelerate.amazonaws.com/documents/cv.pdf");
                            break;
                        }
                    case '4': {
                            $client->sendAudioMessage($to, "https://file-example.s3-accelerate.amazonaws.com/audio/2.mp3");
                            break;
                        }
                    case '5': {
                            $client->sendVoiceMessage($to, "https://file-example.s3-accelerate.amazonaws.com/voice/oog_example.ogg");
                            break;
                        }
                    case '6': {
                            $client->sendVideoMessage($to, "https://file-example.s3-accelerate.amazonaws.com/video/test.mp4");
                            break;
                        }
                    case '7': {
                            $client->sendContactMessage($to, "14000000001@c.us");
                            break;
                        }
                    case '8': {
                            $client->sendChatMessage($to, $ultramsgDictionary->generateRandomSentence());

                            break;
                        }

                    case '9': {
                            $client->sendChatMessage($to, $ultramsgDictionary->generateRandomJoke());
                            break;
                        }

                    case '10': {
                        $caption="image Caption"; 
                            $priority=10;
                            $referenceId="SDK";
                            $nocache=false; 
                            $client->sendImageMessage($to, $ultramsgDictionary->generateRandomImage(), "Random Image", $priority,$referenceId,$nocache);
                            break;
                        }

                        // Incorrect command
                    default: {
                            $this->welcome($message['from'], true);
                            break;
                        }
                }
            }
        }
    }
    public function welcome($to, $noWelcome = false)
    {
        $ultramsg_token="x2epn697nmzwnolg"; // Ultramsg.com token
        $instance_id="instance15890"; // Ultramsg.com instance id
        $client = new WhatsAppApi($ultramsg_token,$instance_id);
        $welcomeStr = ($noWelcome) ? "```📢 Incorrect command 📢 ```\nPlease type one of these *commands*:\n" : "welcome to ultramsg bot Demo \n";
        /*$client->sendChatMessage(
            $to,
            $welcomeStr .
                "\n" .
                "1️⃣ : Show server time.\n" .
                "2️⃣ : Send Image.\n" .
                "3️⃣ : Send Document.\n" .
                "4️⃣ : Send Audio.\n" .
                "5️⃣ : Send Voice.\n" .
                "6️⃣ : Send Video.\n" .
                "7️⃣ : Send Contact.\n" .
                "8️⃣ : Send Random Sentence.\n" .
                "9️⃣ : Send Random Joke.\n" .
                "🔟 : Send Random Image.\n"

        );*/
    }
    public function convert($string)
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic = ['٩', '٨', '٧', '٦', '٥', '٤', '٣', '٢', '١', '٠'];
        $num = range(0, 9);
        $convertedPersianNums = str_replace($persian, $num, $string);
        $englishNumbersOnly = str_replace($arabic, $num, $convertedPersianNums);
        return $englishNumbersOnly;
    }
}
