<?php

namespace Elemke\SicoobPix;

/**
 * Class Cob reúne endpoints destinados a lidar com gerenciamento de cobranças imediatas.
 * @package elemke\sicoobpix
 */
class Cob
{

    private $psp;
    private $clientHttp;

    public function __construct(Psp $psp, bool $debug = false)
    {
        $this->psp = $psp;
        $this->clientHttp = new ClientHttp($psp->getToken(), $debug);
    }

    /**
     * Criar cobrança imediata
     * @param string $txId
     * @param array $body
     * @return string
     * @throws \Exception
     */
    public function criar(array $body, string $txId = null): string
    {
        try {
            $metodo = is_null($txId) ? 'POST' : 'PUT';
            $url = is_null($txId) ? 'cob' : "cob/{$txId}";
            return $this->clientHttp->requisicao($metodo, "{$this->psp->getUrlPix()}/{$url}", $body);
        } catch (\Exception $exc) {
            throw $exc;
        }
    }

    /**
     * Alterar cobrança imediata
     * @param array $body
     * @param string $txId
     * @return string
     * @throws \Exception
     */
    public function alterar(array $body, string $txId): string
    {
        try {
            return $this->clientHttp->requisicao('PATCH', "{$this->psp->getUrlPix()}/cob/{$txId}", $body);
        } catch (\Exception $exc) {
            throw $exc;
        }
    }

    /**
     * Consultar cobranças imediatas
     * @param string|null $txId
     * @param array|null $parametros
     * @return string
     * @throws \Exception
     */
    public function consultar(string $txId = null, array $parametros = null): string
    {
        try {
            if (is_null($txId) && is_null($parametros)) {
                throw new \Exception('Obrigatório algum parâmetro para consulta');
            }
            if (!is_null($txId)) {
                $url = "cob/{$txId}";
            } else {
                $queryString = http_build_query($parametros);
                $url = "cob?{$queryString}";
            }
            return $this->clientHttp->requisicao('GET', "{$this->psp->getUrlPix()}/{$url}");
        } catch (\Exception $exc) {
            throw $exc;
        }
    }

}