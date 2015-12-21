<?php
namespace Stacey\Emoji\FunctionsDir;

use \Firebase\JWT\JWT;

/**
 * A class for decoding the JSON Web Token.
 *
 * @author Stacey Achungo
 */

class Reusable
{

    /**
     * Decodes the JSON Web Token received as a request header.
     *
     * @param string $authHeader Bearer <token string>
     * @return array The decoded payload of the JWT
     */

    public static function tokenVerify($authHeader)
    {
        $tokenDecoded_array = [];
        if ($authHeader) {
                $jwt = substr($authHeader, 7);

            if ($jwt) {
                try {
                    JWT::$leeway = 60;
                    $tokenDecoded = JWT::decode($jwt, $GLOBALS['key'], array('HS256'));
                    $tokenDecoded_array = (array)$tokenDecoded;
                } catch (\Exception $e) {
                        echo "Unauthorized! " . $e->getMessage();
                }
            }
        } else {
                echo json_encode(array(
                    'status' => 'Bad request',
                    'message' => "No token from Authorization header!",
                    ));
        }
        return $tokenDecoded_array;
    }
}
