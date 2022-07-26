<?php
// Файлы phpmailer
require 'PHPMailer.php';
require 'SMTP.php';
require 'Exception.php';

$json = file_get_contents('php://input');
$obj = json_decode($json, true);

// Переменные, которые отправляет пользователь
$type = $obj['type'];
$property = $obj['property'];
$number = $obj['number'];
$from = $obj['from'];
$to = $obj['to'];
$tel = $obj['tel'];

// Формирование самого письма
$title = "Заголовок письма";
$body = "
    <h2>Новое письмо</h2>
    <b>Вид ремонта:</b> $type<br>
    <b>Тип недвижимости:</b> $property<br>
    <b>Количество комнат:</b> $number<br>
    <b>От, м2:</b> $from<br>
    <b>До, м2:</b> $to<br>
    <b>Телефон:</b> $tel<br><br>
";

//Настройки PHPMailer
$mail = new PHPMailer\PHPMailer\PHPMailer();
try {
    $mail->isSMTP();   
    $mail->CharSet = "UTF-8";
    $mail->SMTPAuth   = true;
    $mail->SMTPDebug = 2;
    $mail->Debugoutput = function($str, $level) {$GLOBALS['status'][] = $str;};

    $mail->Host       = 'smtp.gmail.com'; // SMTP сервера вашей почты
    $mail->Username   = 'bogdan200255@gmail.com'; // Логин на почте
    $mail->Password   = 'tbetrhtvymvzjefu'; // Пароль на почте
    $mail->SMTPSecure = 'ssl';
    $mail->Port       = 465;

    $mail->setFrom('bogdan200255@gmail.com', 'batlovb'); // Адрес самой почты и имя отправителя

    $mail->addAddress('bogdan200255@gmail.com');  

    // Отправка сообщения
    $mail->isHTML(true);
    $mail->Subject = $title;
    $mail->Body = $body;    

    // Проверяем отравленность сообщения
    if ($mail->send()) {$result = "success";} 
    else {$result = "error";}

} catch (Exception $e) {
    $result = "error";
    $status = "Сообщение не было отправлено. Причина ошибки: {$mail->ErrorInfo}";
}

// Отображение результата
echo json_encode(["result" => $result, "status" => $status]);