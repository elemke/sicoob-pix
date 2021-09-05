## Pacote integração PIX Sicoob

Esse pacote oferece integração com a API PIX do sistema Sicoob, conforme documentação do Banco Central do Brasil.

### Observação
<hr>
Os endpoints disponibilizados por este pacote seguem a padronização do Banco Central (documentação aqui). Entretanto, o provedor de serviços de pagamento (PSP) pode não implementar todos eles.

### Instalação

```phpt
composer require elemke/sicoob-pix
```

### Configurações Iniciais

Configure as variáveis do pacote no seu arquivo .env
```phpt
SICOOBPIX_CLIENT_ID='xxxxx'
SICOOBPIX_CLIENT_SECRET='xxxxx'
SICOOBPIX_AMBIENTE_HOMOLOGACAO=true
SICOOBPIX_CAMINHO_CERT_PUBLICO='./path/file.pem'
SICOOBPIX_SENHA_CERT_PUBLICO='xxx'
SICOOBPIX_CAMINHO_CERT_PRIVADO='./path/file.key'
SICOOBPIX_SENHA_CERT_PRIVADO='xxx'
```
Caso tenha dúvidas de como obter esses dados, consulte o site do Sicoob Developers através do link: https://developers.sicoob.com.br

### Exemplos de Uso
#### Criar cobrança imediata
```phpt
$scope = ['cob.read', 'cob.write'];
$psp = new Psp($scope);

$cobranca = [
    'calendario' => [
        'expiracao' => 3600
    ],
    'devedor' => [
        'cpf' => '12345678911',
        'nome' => 'Fulano'
    ],
    'valor' => [
        'original' => '1.00'
    ],
    'chave' => 'teste@teste.com',
    'solicitacaoPagador' => 'mensagem pagador'
];

$cob = new Cob($psp);
$cob->criar($cobranca);
```


