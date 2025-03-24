<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #ffffff;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }

        .contact-container {
            background: #fff;
            width: 400px;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 128, 0, 0.2);
            text-align: center;
        }

        .contact-container h2 {
            color: #008000;
            margin-bottom: 20px;
            font-size: 24px;
        }

        input, textarea {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            border: 2px solid #008000;
            border-radius: 6px;
            font-size: 16px;
            outline: none;
            transition: all 0.3s;
        }

        input:focus, textarea:focus {
            border-color: #004d00;
            box-shadow: 0px 0px 5px rgba(0, 128, 0, 0.4);
        }

        button {
            width: 100%;
            background: #008000;
            color: white;
            border: none;
            padding: 12px;
            margin-top: 15px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background: #004d00;
        }

        .back-button {
            width: 100%;
            background: #ccc;
            color: #333;
            margin-top: 10px;
            font-size: 16px;
        }

        .back-button:hover {
            background: #999;
        }

        #respuesta {
            margin-top: 15px;
            font-size: 16px;
        }

    </style>
</head>
<body>

    <div class="contact-container">
        <h2>Contacto</h2>

        <form id="contactForm" action="https://formspree.io/f/TU_ID_AQUÍ" method="POST">
            <input type="text" name="nombre" placeholder="Tu Nombre" required>
            <input type="email" name="email" placeholder="Tu Correo Electrónico" required>
            <textarea name="mensaje" rows="5" placeholder="Escribe tu mensaje aquí..." required></textarea>
            <button type="submit">Enviar Mensaje</button>
        </form>

        <p id="respuesta"></p>

        <button class="back-button" onclick="window.location.href='javascript:history.back()';">
            Volver Atrás
        </button>
    </div>

    <script>
        document.getElementById("contactForm").addEventListener("submit", async function(event) {
            event.preventDefault();

            let form = event.target;
            let formData = new FormData(form);

            let response = await fetch(form.action, {
                method: "POST",
                body: formData,
                headers: { "Accept": "application/json" }
            });

            let mensaje = document.getElementById("respuesta");
            if (response.ok) {
                mensaje.textContent = "¡Gracias! Tu mensaje ha sido enviado.";
                mensaje.style.color = "#008000";
                form.reset();
            } else {
                mensaje.textContent = "Hubo un error al enviar el mensaje.";
                mensaje.style.color = "red";
            }
        });
    </script>

</body>
</html>
