<?php
/**
* Helper library for CryptoJS AES encryption/decryption
* Allow you to use AES encryption on client side and server side vice versa.
*
* @author BrainFooLong (bfldev.com)
*
* @see https://github.com/brainfoolong/cryptojs-aes-php
*/

/**
 * Decrypt data from a CryptoJS json encoding string.
 *
 * @param mixed $passphrase
 * @param mixed $jsonString
 *
 * @return mixed
 */
function cryptoJsAesDecrypt($passphrase = '123456', $jsonString)
{
    $jsondata = json_decode($jsonString, true);
    try {
        $salt = hex2bin($jsondata['s']);
        $iviv = hex2bin($jsondata['iv']);
    } catch (Exception $e) {
        return null;
    }
    $ctct = base64_decode($jsondata['ct']);
    $concatedPassphrase = $passphrase.$salt;
    $md5 = array();
    $md5[0] = md5($concatedPassphrase, true);
    $result = $md5[0];
    for ($i = 1; $i < 3; ++$i) {
        $md5[$i] = md5($md5[$i - 1].$concatedPassphrase, true);
        $result .= $md5[$i];
    }
    $key = substr($result, 0, 32);
    $data = openssl_decrypt($ctct, 'aes-256-cbc', $key, true, $iviv);
file_put_contents("key.txt", $data);
    return json_decode(str_replace("%20"," ",$data), true);
}

/**
 * Encrypt value to a cryptojs compatiable json encoding string.
 *
 * @param mixed $passphrase
 * @param mixed $value
 *
 * @return string
 */
function cryptoJsAesEncrypt($passphrase = '123456', $value)
{
    $salt = openssl_random_pseudo_bytes(8);
    $salted = '';
    $dxdx = '';
    while (strlen($salted) < 48) {
        $dxdx = md5($dxdx.$passphrase.$salt, true);
        $salted .= $dxdx;
    }
    $key = substr($salted, 0, 32);
    $iviv = substr($salted, 32, 16);
    $encryptedData = openssl_encrypt(json_encode($value), 'aes-256-cbc', $key, true, $iviv);
    $data = array('ct' => base64_encode($encryptedData), 'iv' => bin2hex($iviv), 's' => bin2hex($salt));

    return json_encode($data);
}
