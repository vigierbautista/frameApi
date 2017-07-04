<?php
/**
 * Clase que se encarga del manejo de los tokens generados con JWT
 * User: Bautista
 * Date: 3/7/2017
 * Time: 12:22 AM
 */

namespace FrameApi\Security;

use Emarref\Jwt\Claim;
use Emarref;

/**
 * Class Token
 * @package FrameApi\Security
 */
class Token
{
    /**
     * Clave secreta de la aplicación para generar los tokens.
     * @var string
     */
    private static $app_secret = 'rhtoruygowawehfpwaefihaweofa23';

    /**
     * Crea un token de autenticación para el usuario.
     *
     * @param int $idUser El id del usuario.
     * @return string El token.
     */
    public static function createToken($idUser) {
        // Creamos el token (payload).
        $token = new Emarref\Jwt\Token();

        // Agregamos los claims (afirmaciones) que queremos utilizar.
        // Indicamos quién es el que emite el token.
        $token->addClaim(new Claim\Issuer('frameApi'));
        // Indicamos la fecha en la que se emite.
        $token->addClaim(new Claim\IssuedAt(new \DateTime('now')));
        // Indicamos nuestras afirmaciones particulares.
        $token->addClaim(new Claim\PrivateClaim('id_usuario', $idUser));

        // Ahora que tenemos definido el Payload, creamos el JWT.
        $jwt = new Emarref\Jwt\Jwt();

        // Definimos el algoritmo y la encriptación que queremos utilizar.
        $algoritmo = new Emarref\Jwt\Algorithm\Hs256(self::$app_secret);
        $encriptacion = Emarref\Jwt\Encryption\Factory::create($algoritmo);

        // Finalmente, serializamos el Token, y generamos el JWT final.
        return $jwt->serialize($token, $encriptacion);
    }

    /**
     * Retorna el array con los datos del token si el
     * $token es válido. null en caso contrario.
     *
     * @param string $token El token a verificar.
     * @return array El array de datos del token o null o datos de error
     */
    public static function verifyToken($token) {
        // Verificamos si $token está vacío.
        if(is_null($token)) {
            return null;
        }

        // Creamos la instancia de JWT.
        $jwt = new Emarref\Jwt\Jwt();

        // Definimos el algoritmo y la encriptación que queremos utilizar.
        $algoritmo = new Emarref\Jwt\Algorithm\Hs256(self::$app_secret);
        $encriptacion = Emarref\Jwt\Encryption\Factory::create($algoritmo);

        // Definimos el "contexto" de verificación.
        $contexto = new Emarref\Jwt\Verification\Context($encriptacion);

        // Definimos las afirmaciones (claims) que queremos verificar.
        $contexto->setIssuer('frameApi');

        // Deserializamos el token que recibimos.
        $tokenDeserializado = $jwt->deserialize($token);

        // Verificamos!
        try {
            // Verificamos si el token es correcto.
            // De no serlo, lanza una Exception, que atrapamos en el catch.
            $jwt->verify($tokenDeserializado, $contexto);

            // Obtenemos los datos del Token.
            $salida = self::getTokenData($tokenDeserializado);

            // El token es válido! Retornamos los datos.
            return $salida;
        } catch(Emarref\Jwt\Exception\VerificationException $e) {

            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * @param Emarref\Jwt\Token $tokenDeserializado
     * @return array
     */
    public static function getTokenData($tokenDeserializado)
    {
        // Obtenemos el payload.
        $payload = $tokenDeserializado->getPayload();
        // Obtenemos los claims.
        $claims = $payload->getClaims();
        // Obtenemos las propiedades.
        $propiedades = $claims->getIterator();
        // Obtenemos los valores que queremos.
        $salida = [];
        $salida['id_usuario'] = $propiedades['id_usuario']->getValue();

        return $salida;
    }

}