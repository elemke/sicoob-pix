<?php

namespace Elemke\SicoobPix;

use Dotenv\Dotenv;

class Psp
{
    private $urlToken;
    private $scope;
    private $baseUrlPix;
    private $certificadoPublico;
    private $certificadoPrivado;
    private $token;

    /**
     * Realiza conexão com o ambiente do Sicoob
     * @param array $scope define qual escopo da conexao
     */
    public function __construct(array $scope)
    {
        $path = './';
        if (strpos(realpath(__DIR__), 'public')) {
            $path = "../";
        }
        $dotenv = Dotenv::createImmutable($path);
        $dotenv->load();
        $this->scope = implode(' ', $scope);
        $this->urlToken = $_ENV['SICOOBPIX_AMBIENTE_HOMOLOGACAO'] ? Endpoint::TOKEN_HOMOLOGACAO : Endpoint::TOKEN_PRODUCAO;
        $this->baseUrlPix = $_ENV['SICOOBPIX_AMBIENTE_HOMOLOGACAO'] ? Endpoint::PIX_HOMOLOGACAO : Endpoint::PIX_PRODUCAO;
        $this->certificadoPublico = [$_ENV['SICOOBPIX_CAMINHO_CERT_PUBLICO'], $_ENV['SICOOBPIX_SENHA_CERT_PUBLICO']];
        $this->certificadoPrivado = [$_ENV['SICOOBPIX_CAMINHO_CERT_PRIVADO'], $_ENV['SICOOBPIX_SENHA_CERT_PRIVADO']];
    }


    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function gerarToken(): void
    {
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->request('POST', $this->urlToken, [
                'form_params' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => $_ENV['SICOOBPIX_CLIENT_ID'],
                    'client_secret' => $_ENV['SICOOBPIX_CLIENT_SECRET'],
                    'scope' => $this->scope
                ],
                'cert' => $this->certificadoPublico,
                'ssl_key' => $this->certificadoPrivado
            ]);
            $this->token = $response->getBody()->getContents();
        } catch (\Exception $exc) {
            throw $exc;
        }
    }

    /**
     * Token para requisições ao Sicoob
     * @return string
     */
    public function getToken(): string
    {
        if (is_null($this->token)) {
            $this->gerarToken();
        }
        $token = json_decode($this->token);
        $tokenExpiracao = $token->consented_on + $token->expires_in;
        if ($tokenExpiracao < time()) {
            $this->gerarToken();
        }
        $token = json_decode($this->token);
        return $token->access_token;
    }

    /**
     * Retorna url pix do ambiente informado
     * @return string
     */
    public function getUrlPix(): string
    {
        return $this->baseUrlPix;
    }

    /**
     * Retorna certificado publico
     * @return array
     */
    public function getCertificadoPublico(): array
    {
        return $this->certificadoPublico;
    }

    /**
     * Retorna certificado privado
     * @return array
     */
    public function getCertificadoPrivado(): array
    {
        return $this->certificadoPrivado;
    }


}