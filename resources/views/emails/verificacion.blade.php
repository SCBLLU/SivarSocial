<body>
  <table cellpadding="0" cellspacing="0" width="100%" style="font-family: Arial, sans-serif; background-color: #ffffff; padding: 20px;">
    <tr>
      <td align="center">
        <table cellpadding="0" cellspacing="0" width="600" style="border: 1px solid #dddddd; padding: 20px; text-align: center;">
          <tr>
            <td>
              <img src="https://res.cloudinary.com/dj848z4er/image/upload/v1748745136/tokhsr71m0thpsjaduyc.png" alt="LOGO" width="110" height="45" style="margin-bottom: 20px;">
            </td>
          </tr>
          <tr>
            <td>
              <h2 style="margin-bottom: 10px;">Contraseña Restaurada</h2>
              <p style="font-size: 16px; margin-bottom: 20px;">
                Hola👋, te informamos que tu contraseña<br>
                ha sido cambiada correctamente.
              </p>
              <a href="{{ route('login') }}">
                <p style="background-color: #0f02a4; color: #ffffff; font-size: 24px; padding: 12px 24px; display: inline-block; border-radius: 10px; margin-bottom: 20px;">
                Iniciar Sessión
              </p>
              </a>
               <p style="font-size: 16px; margin-bottom: 20px;">
                Te recomendamos guardar tu contraseña<br>
                en un lugar seguro.
              </p>
              <hr style="border: none; border-top: 1px solid #0f02a4; margin: 20px auto; width: 80%;">
              <p style="font-size: 14px; color: #555555;">
                Si no reconoces esta acción, te recomendamos <a href="{{ route('recuperar') }}">recuperar</a> tu cuenta.
              </p>
              <p style="font-size: 14px; color: #555555;">
                Saludos, <br>
                El equipo de la doble S
              </p>
              <hr style="border: none; border-top: 0.5px solid #0f02a4; margin: 20px auto; width: 60%;">
              <p style="font-size: 12px; color: #555555;">
                Este mensaje fue enviado a <a href="mailto:{{ $email }}" style="color: #555555; text-decoration: underline;">{{ $email }}</a>
              </p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>  
</body>
