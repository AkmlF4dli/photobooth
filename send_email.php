<?php
require_once __DIR__ . '/vendor/autoload.php';

use SendinBlue\Client\Configuration;
use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Model\SendSmtpEmail;
use SendinBlue\Client\Model\SendSmtpEmailSender;
use SendinBlue\Client\Model\SendSmtpEmailTo;
use SendinBlue\Client\Model\SendSmtpEmailAttachment;

function sendPhotoEmail($recipientEmail, $imagePath)
{
    $apiKey = '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';
 
    // Konfigurasi API
    $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', $apiKey);
    $apiInstance = new TransactionalEmailsApi(null, $config);

    // Data pengirim
    $sender = new SendSmtpEmailSender([
        'name' => 'Eightcs Company',
        'email' => 'eightics08@gmail.com'  // Ganti dengan email kamu
    ]);

    // Data penerima
    $to = [new SendSmtpEmailTo(['email' => $recipientEmail])];

    // Attachment (jika ingin kirim gambar)
    $attachments = [];
    if (file_exists($imagePath)) {
        $attachments[] = new SendSmtpEmailAttachment([
            'name' => basename($imagePath),
            'content' => base64_encode(file_get_contents($imagePath))
        ]);
    }

    // Email content
    $emailData = new SendSmtpEmail([
        'sender' => $sender,
        'to' => $to,
        'subject' => 'Halo! Ini dia fotomu!',
        'htmlContent' => '<html><body><h1>Hello!</h1><p>Here is the photo you requested.</p></body></html>',
        'attachment' => $attachments
    ]);

    // Kirim email
    try {
        //JANGAN DICOMMAND
        $result = $apiInstance->sendTransacEmail($emailData);
       // echo "Email sent successfully!\n";
       // print_r($result);
    } catch (Exception $e) {
        echo 'Error while sending email: ' . $e->getMessage() . PHP_EOL;
    }
    $attachments = [];
}
