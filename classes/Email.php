<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email
{
    protected $email;
    protected $name;
    protected $token;

    public function __construct($email, $name, $token)
    {
        $this->email = $email;
        $this->name = $name;
        $this->token = $token;
    }

    public function sentConfirmation()
    {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host =  $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username =  $_ENV['EMAIL_USER'];
        $mail->Password =  $_ENV['EMAIL_PASS'];

        $mail->setFrom('funifelt@funifelt.com', 'Funifelt');
        $mail->addAddress('cliente@funifelt.com', 'Cliente');
        $mail->Subject = translate('confirm_account_funifelt');

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $content = '<html>';
        $content .= '<p>' . translate('hello') . '<strong> ' . $this->name . '</strong> ' . translate('create_account_message_email') . '</p>';
        $content .= '<p> ' . translate('click_here') . ': <a href="' . $_ENV['APP_URL'] . '/confirm?token=' . $this->token . '">' . translate('confirm_account') . '</a></p>';
        $content .= '<p>' . translate('ignore_message_email') . '</p>';
        $content .= '</html>';

        $mail->Body = $content;

        // Send the email
        $mail->send();
    }


    public function sentInsructions()
    {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host =  $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username =  $_ENV['EMAIL_USER'];
        $mail->Password =  $_ENV['EMAIL_PASS'];

        $mail->setFrom('funifelt@funifelt.com', 'Funifelt');
        $mail->addAddress('cliente@funifelt.com', 'Cliente');
        $mail->Subject = translate('confirm_account_funifelt');

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $content = '<html>';
        $content .= '<p>' . translate('hello') . '<strong> ' . $this->name . '</strong> ' . translate('reset_password_message_email') . '</p>';
        $content .= '<p> ' . translate('click_here') . ': <a href="' . $_ENV['APP_URL'] . '/reset?token=' . $this->token . '">' . translate('reset_password') . '</a></p>';
        $content .= '<p>' . translate('ignore_message_password') . '</p>';
        $content .= '</html>';

        $mail->Body = $content;

        // Send the email
        $mail->send();
    }
}
