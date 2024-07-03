<?php
use PHPMailer\PHPMailer\PHPMailer;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

class VerificationCode
{
    public $smtpHost;
    public $smtpPort;
    public $sender;
    public $password;
    public $receiver;
    public $code;

    public function __construct($receiver,$code)
    {
        /**
         * Your email id  
         * For example : johndoe@gmail.com
         * contact@johndoe.com
         * 
         */
        $this->sender = "beeventful16@gmail.com";
        $this->password = "uvyexgqfjpttlnhp ";
        $this->receiver = $receiver;
        $this->smtpHost = "smtp.gmail.com";
        $this->smtpPort = 587;
        $this->code = $code;
    }
    public function sendMail()
    {
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        $mail->Host = $this->smtpHost;
        $mail->Port = $this->smtpPort;
        $mail->IsHTML(true);
        $mail->Username = $this->sender;
        $mail->Password = $this->password;
        $mail->Body = $this->getHTMLMessage();
        $mail->Subject = "Registeration OTP";
        $mail->SetFrom($this->sender, "Confirm Registeration");
        $mail->AddAddress($this->receiver);
        $mail->send();
    }

    public function getHTMLMessage()
    {
        $htmlMessage = <<<MSG
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin-left: 40px;
                    margin-right: 40px;
                }
                .otp-container {
                    background-color: #f0f5ff; /* Light blue background for OTP */
                    padding: 10px;
                    border-radius: 5px;
                    text-align: center;
                }
                .logo {
                    max-width: 200px;
                    display: block;
                    margin: 0 auto;
                }

                h2 {
                    text-align: center;
                }
            </style>
        </head>
        <body>
            <h2>BeEventful</h2>
            <p>
                Hi,<br> Welcome to BeEventful".
            </p>
            <p>
            Please use this OTP for confirming the registration.
            </p>
            <div class="otp-container">
                <h2 style="color: #007bff;">{$this->code}</h2>
            </div>
            <p>
                This OTP is valid for only 2 minutes. <br><br
                This is an automated email. Please do not reply to this message.
            </p>
            <p><br>
                Best regards,<br>BeEventful Team.
            </p>
        </body>
        </html>
    MSG;
        return $htmlMessage;
    }

}

// $vc = new VerificationCode('ayshamulla9313@gmail.com',879586);
// $vc->sendMail();
