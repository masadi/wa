<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use UltraMsg\WhatsAppApi;

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
    }
}
