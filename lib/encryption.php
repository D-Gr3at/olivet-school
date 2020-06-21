<?php

$des = new encrypt();


//echo $des->hexToBase64("7f16c3f520d707d173306329f165b132");

//echo $des->encrypt("I love you","CE51E06875F7D");

//$key = $des->hexToStr("0123456789ABCDEFFEDCBA9876543210");

//echo $des->decrypt("fxbD9SDXB9FzMGMp8WWxMg==","CE51E06875F7D964");


Class encrypt {
	
public function hexToBase64($hex)
{
  return	base64_encode(pack('H*',$hex));
}
	
public function hexToStr($hex)
{
    $string='';
    for ($i=0; $i < strlen($hex)-1; $i+=2)
    {
        $string .= chr(hexdec($hex[$i].$hex[$i+1]));
    }
    return $string;
}


public function strToHex($string)
{
    $hex='';
    for ($i=0; $i < strlen($string); $i++)
    {
        $hex .= dechex(ord($string[$i]));
    }
    return $hex;
}
	
	
	
public function doencrypt($data)
{
   // $secret="Abxcjnjncs;28097348jkndkol!";
    $secret="aCCeSSCE51E06875F7AAdeEsEiNo";
    //Generate a key from a hash
    $key = md5(utf8_encode($secret), true);

    //Take first 8 bytes of $key and append them to the end of $key.
    $key .= substr($key, 0, 8);

    //Pad for PKCS7
    $blockSize = mcrypt_get_block_size('tripledes', 'ecb');
    $len = strlen($data);
    $pad = $blockSize - ($len % $blockSize);
    $data .= str_repeat(chr($pad), $pad);

    //Encrypt data
    $encData = mcrypt_encrypt('tripledes', $key, $data, 'ecb');

    return base64_encode($encData);
}
 
public function decrypt($data)
{
    $secret="aCCeSSCE51E06875F7AAdeEsEiNo";
    //Generate a key from a hash
    $key = md5(utf8_encode($secret), true);

    //Take first 8 bytes of $key and append them to the end of $key.
    $key .= substr($key, 0, 8);

    $data = base64_decode($data);

    $data = mcrypt_decrypt('tripledes', $key, $data, 'ecb');

    $block = mcrypt_get_block_size('tripledes', 'ecb');
    $len = strlen($data);
    $pad = ord($data[$len-1]);

    return substr($data, 0, strlen($data) - $pad);
}
}
?>