<?php

class jwt{
    public function __construct(private string $keyf) {
         
    }
    private function base64URLencode(string $text):string{
        return str_replace(['+','/','='],['-','_',''],base64_encode($text));
    }

    public function encode(array $payloud):string{
        #header base64 encode
        $header=json_encode(["alg"=>"Hs256","typ"=>"jwt"]);
        $header=$this->base64URLencode($header);

        #payload base64 encode
        $payloud=json_encode($payloud);
        $payloud=$this->base64URLencode($payloud);
        
        #signatur kısmı
        $signature=hash_hmac("sha256",$header.".".$payloud,$this->keyf,true);
        $signature=$this->base64URLencode($signature);

        return $header.".".$payloud.".".$signature;

    

    }
    private function base64URLDecode(string $text): string
    {
        return base64_decode(
            str_replace(
                ["-", "_"],
                ["+", "/"],
                $text
            )
        );
    }
    public function decode(string $token): array
    {
        if (
            preg_match(
                "/^(?<header>.+)\.(?<payload>.+)\.(?<signature>.+)$/",
                $token,
                $matches
            ) !== 1
        ) {

            throw new InvalidArgumentException("invalid token format");
        }

        $signature = hash_hmac(
            "sha256",
            $matches["header"] . "." . $matches["payload"],
            $this->key,
            true
        );

        $signature_from_token = $this->base64URLDecode($matches["signature"]);

        if (!hash_equals($signature, $signature_from_token)) {

            // throw new Exception("signature doesn't match");
            throw new Exception;
        }

        $payload = json_decode($this->base64URLDecode($matches["payload"]), true);

        return $payload;
    }
}

?>