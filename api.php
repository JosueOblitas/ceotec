<?php
// Cargamos las dependencias de PHPMailer
require __DIR__ . '/vendor/autoload.php';

// Importamos las clases de PHPMailer que vamos a utilizar
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Iniciamos una sesión de PHP para manejar datos de sesión
session_start();

// Función para obtener la dirección IP del cliente
function getClientIP()
{
    // Si hay una IP en la cabecera HTTP_CLIENT_IP, la retornamos
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    }
    // Si hay una IP en la cabecera HTTP_X_FORWARDED_FOR, la retornamos
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    // Si no, retornamos la IP remota del cliente
    else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

// Función para verificar si una dirección IP está bloqueada
function isIPBlocked($ip)
{
    // Cargamos las IPs bloqueadas desde el archivo y las almacenamos en un array
    $blockedIPs = file('blocked_ips.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    // Verificamos si la IP proporcionada está en la lista de IPs bloqueadas
    return in_array($ip, $blockedIPs);
}

// Función para bloquear una dirección IP
function blockIP($ip)
{
    // Agregamos la IP proporcionada al archivo de IPs bloqueadas
    file_put_contents('blocked_ips.txt', $ip . PHP_EOL, FILE_APPEND);
}

// Función para verificar si se puede enviar un correo electrónico
function canSendEmail()
{
    // Obtenemos el tiempo del último correo enviado almacenado en la sesión
    $lastSentTime = $_SESSION['last_sent_time'] ?? 0;
    // Obtenemos el tiempo actual
    $currentTime = time();
    // Verificamos si ha pasado al menos 300 segundos (5 minutos) desde el último correo
    return ($currentTime - $lastSentTime) >= 300;
}

// Función para actualizar el tiempo del último correo electrónico enviado
function updateLastSentTime()
{
    // Actualizamos el tiempo del último correo enviado en la sesión
    $_SESSION['last_sent_time'] = time();
}

// Función para enviar un correo electrónico utilizando PHPMailer
function sendEmail($to, $subject, $message)
{
    // Creamos una instancia de PHPMailer
    $mail = new PHPMailer(true);
    try {
        // Configuramos la instancia de PHPMailer para usar SMTP
        $mail->isSMTP();
        $mail->Host = "mail.ceotec.pe";
        $mail->SMTPAuth = false;
        $mail->Username = "formluariocontacto@ceotec.pe";
        $mail->Password = "6)k)eNRy_X7g";
        $mail->Port = 25;
        // Configuramos el remitente y destinatario del correo
        $mail->setFrom('formluariocontacto@ceotec.pe', 'Formulario web');
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->Body = $message;

        // Enviamos el correo
        $mail->send();
        // Actualizamos el tiempo del último correo enviado
        updateLastSentTime();
        // Indicamos éxito en el envío
        return true;
    } catch (Exception $e) {
        // Bloqueamos la IP en caso de error y devolvemos el mensaje de error
        blockIP(getClientIP());
        return $e->getMessage();
    }
}

// Verificamos si la IP está bloqueada
if (isIPBlocked(getClientIP())) {
    echo "Tu IP está bloqueada debido a múltiples intentos fallidos. Contáctanos para desbloquearla.";
} else {
    // Verificamos si se puede enviar un correo electrónico
    if (canSendEmail()) {
        // Verificamos si la solicitud es de tipo POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtenemos y decodificamos los datos del formulario en formato JSON
            $data = json_decode(file_get_contents("php://input"), true);

            // Definimos los campos obligatorios
            $requiredFields = array("nombre", "apellido", "correo", "mensaje", "phone");
            $missingFields = array();

            // Verificamos si los campos obligatorios están presentes
            foreach ($requiredFields as $field) {
                if (!isset($data[$field]) || empty($data[$field])) {
                    $missingFields[] = $field;
                }
            }

            // Si hay campos obligatorios faltantes, generamos un mensaje de error
            if (count($missingFields) > 0) {
                $response = array("error" => "Los siguientes campos son obligatorios: " . implode(", ", $missingFields));
            } else {
                // Si todos los campos están presentes, construimos el correo y lo enviamos
                $to = "ventas@ceotec.pe";
                $subject = "Contacto de " . $data["nombre"] . " " . $data["apellido"] . ". Número de Teléfono:" . $data["phone"];
                $message = $data["mensaje"];

                // Enviamos el correo y verificamos si se envió con éxito
                $isSend = sendEmail($to, $subject, $message);
                if ($isSend === true) {
                    $response = array(
                        "message" => "Formulario completo recibido con éxito y correo enviado.",
                        "type" => "success",
                        "icon" => " <i class='uil uil-check-circle'></i>"
                    );
                } else {
                    $response = array(
                        "message" => "Error al enviar el correo, itentelo más tarde $isSend",
                        "type" => "danger",
                        "icon" => "<i class='uil uil-times-circle'></i>"
                    );
                }
            }

            // Devolvemos la respuesta en formato JSON
            header('Content-Type: application/json');
            echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } else {
            // Devolvemos un mensaje de error si la solicitud no es de tipo POST
            $response = array(
                "message" => "Recuerda que solo se admite peticiones POST, te estamos guardando la ip donde estas haciendo la solicitud, ten cuidado con lo que haces",
                "type" => "danger",
                "icon" => "<i class='uil uil-times-circle'></i>"
            );
        }
    } else {
        echo json_encode(
            array(
                "message" => "Por medidas de seguridad, solo peudes enviar enviar el fromulario cada 5 minutos",
                "type" => "warning ",
                "icon" => " <i class='uil uil-exclamation-triangle'></i>"
            )
        );
    }
}
?>