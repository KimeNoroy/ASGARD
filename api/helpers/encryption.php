<?php
require_once('config.php');

class Encryption {
    private static $key = '$G7!vF@2kZx5&uLp3'; // Cambia esto por tu clave secreta
    private static $secret = 'S3CR3T10N1NT3RF3R3NC3PR3F3R3NC3INS1STSQU4R3G14NT'; // Define tu variable secreta
    private static $fixed_iv = '3875106926685708'; // IV fijo (16 bytes para AES-128)

    // Método estático para cifrar
    public static function aes128_ofb_encrypt($plaintext) {
        // Combina la clave con el secreto para mayor seguridad
        $combined_key = substr(hash('sha256', self::$key . self::$secret, true), 0, 16);
        
        // Cifrar el texto plano utilizando AES-128-OFB con un IV fijo
        $ciphertext = openssl_encrypt($plaintext, 'AES-128-OFB', $combined_key, OPENSSL_RAW_DATA, self::$fixed_iv);
        
        // Devolver el texto cifrado en base64
        return base64_encode($ciphertext);
    }

    // Método estático para descifrar
    public static function aes128_ofb_decrypt($ciphertext) {
        // Decodificar el texto cifrado desde base64
        $ciphertext = base64_decode($ciphertext);

        // Combina la clave con el secreto para mayor seguridad
        $combined_key = substr(hash('sha256', self::$key . self::$secret, true), 0, 16);
        
        // Descifrar el texto cifrado utilizando AES-128-OFB con el IV fijo
        $plaintext = openssl_decrypt($ciphertext, 'AES-128-OFB', $combined_key, OPENSSL_RAW_DATA, self::$fixed_iv);
        
        return $plaintext;
    }
}
