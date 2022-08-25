<?php

namespace Elemke\SicoobPix;

class ClientHttp
{
    private $token;
    private $certificadoPublico;
    private $certificadoPrivado;
    private $debug;

    public function __construct(string $token, bool $debug = false)
    {
        $this->token = $token;
        $this->debug = $debug;
        $this->certificadoPublico = [realpath($_ENV['SICOOBPIX_CAMINHO_CERT_PUBLICO']), $_ENV['SICOOBPIX_SENHA_CERT_PUBLICO']];
        $this->certificadoPrivado = [realpath($_ENV['SICOOBPIX_CAMINHO_CERT_PRIVADO']), $_ENV['SICOOBPIX_SENHA_CERT_PRIVADO']];
    }

    public function requisicao(string $metodo, string $url, array $body = null)
    {
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->request($metodo, $url,
                [
                    'json' => $body,
                    'debug' => $this->debug ?? false,
                    'headers' => [
                        'Authorization' => "Bearer {$this->token}"
                    ],
                    'cert' => $this->certificadoPublico,
                    'ssl_key' => $this->certificadoPrivado
                ]
            );
            return $response->getBody()->getContents();
        } catch (\Exception $exc) {
            throw $exc;
        }
    }
}