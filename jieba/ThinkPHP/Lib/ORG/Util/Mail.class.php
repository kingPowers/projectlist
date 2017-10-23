<?php

vendor('PHPMailer/PHPMailer');

class Mail {

    static function send($address, $title, $content) {
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->ishtml(true);
        $mail->CharSet = 'UTF-8';
        if (is_array($address)) {
            foreach ($address as $to) {
                $mail->AddAddress($to);
            }
        } else {
            $mail->AddAddress($address);
        }
        //$mail->AddCC('futao@futao.name');
        $mail->Body = $content;
        $mail->From = C('mail.from');
        $mail->FromName = C('mail.fromdesc');
        $mail->Subject = $title;
        $mail->Host = C('mail.server');
        $mail->SMTPAuth = true;
        $mail->Username = C('mail.username');
        $mail->Password = C('mail.password');
        return($mail->Send());
    }

}