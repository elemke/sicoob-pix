<?php

namespace Elemke\SicoobPix;

/**
 * Class webhook reúne endpoints destinados a gerenciamento de webhooks.
 * @package elemke\sicoobpix
 */
class Webhook
{
    private $psp;
    private $clientHttp;

    public function __construct(Psp $psp, bool $debug = false)
    {
        $this->psp = $psp;
        $this->clientHttp = new ClientHttp($psp->getToken(), $debug);
    }

    /**
     * Criar webhook
     * @param string $chave
     * @param string $urlWebhook
     * @return bool
     * @throws \Exception
     */
    public function criar(string $chave, string $urlWebhook): bool
    {
        try {
            $webhook = ['webhookUrl' => $urlWebhook];
            $this->clientHttp->requisicao('PUT', "{$this->psp->getUrlPix()}/webhook/{$chave}", $webhook);
            return true;
        } catch (\Exception $exc) {
            throw $exc;
        }
    }

    /**
     * Consultar webhook cadastrado
     * @param string|null $chave
     * @return string
     * @throws \Exception
     */
    public function consultar(string $chave = null): string
    {
        try {
            $url = is_null($chave) ? 'webhook' : "webhook/{$chave}";
            return $this->clientHttp->requisicao('GET', "{$this->psp->getUrlPix()}/$url");
        } catch (\Exception $exc) {
            throw $exc;
        }
    }

    /**
     * Excluir webhook
     * @param string $chave
     * @return bool
     * @throws \Exception
     */
    public function deletar(string $chave)
    {
        try {
            $this->clientHttp->requisicao('DELETE', "{$this->psp->getUrlPix()}/webhook/{$chave}");
            return true;
        } catch (\Exception $exc) {
            throw $exc;
        }
    }

}