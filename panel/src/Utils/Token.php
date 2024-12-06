<?php 
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Plain;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\ValidAt;
use Lcobucci\Clock\SystemClock;
use DateTimeImmutable;

// require '../../vendor/autoload.php';

function createJwtToken(string $data, string $secretKey): string {
    $config = Configuration::forSymmetricSigner(new Sha256(), InMemory::plainText($secretKey));

    $now = new DateTimeImmutable();
    $token = $config->builder()
        ->issuedBy('your-issuer') // Emisor del token
        ->issuedAt($now) // Hora de emisión
        ->expiresAt($now->modify('+1 day')) // Duración del token (24 horas)
        ->withClaim('data', $data) // Datos a codificar
        ->getToken($config->signer(), $config->signingKey());

    return $token->toString();
}

function decodeJwtToken(string $tokenStr, string $secretKey): ?string {
    $config = Configuration::forSymmetricSigner(new Sha256(), InMemory::plainText($secretKey));

    try {
        $token = $config->parser()->parse($tokenStr);

        if (!$token instanceof Plain) {
            throw new \InvalidArgumentException('Token no válido.');
        }

        $now = new DateTimeImmutable();
        $clock = new SystemClock($now->getTimezone());

        $constraints = [
            new IssuedBy('your-issuer'),
            new ValidAt($clock)
        ];

        if ($config->validator()->validate($token, ...$constraints)) {
            return $token->claims()->get('data');
        } else {
            return null;
        }
    } catch (\Exception $e) {
        // Manejo de errores en caso de token inválido o expirado
        return null;
    }
}
/*
$secretKey = 'your-secret-key';
$data = 'your-string-to-encode';

// Crear un token JWT
$jwt = createJwtToken($data, $secretKey);
echo "Token JWT: " . $jwt . "\n";

// Decodificar el token JWT
$decodedData = decodeJwtToken($jwt, $secretKey);
if ($decodedData !== null) {
    echo "Datos decodificados: " . $decodedData . "\n";
} else {
    echo "Token inválido o expirado.\n";
}
*/