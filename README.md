## Pacote integração PIX Sicoob

Esse pacote oferece integração com a API PIX do sistema Sicoob, conforme documentação do Banco Central do Brasil.

#### Observação

Os endpoints disponibilizados por este pacote seguem a padronização do Banco Central: [Link Documentação](https://bacen.github.io/pix-api/). Entretanto, o provedor de serviços de
pagamento (PSP) pode não implementar todos eles.


<hr>

### Instalação

```phpt
composer require elemke/sicoob-pix
```

### Configurações Iniciais

Configure as variáveis do pacote no seu arquivo .env (caso ele não exista, crie na pasta raiz do seu projeto)

```phpt
SICOOBPIX_CLIENT_ID='xxxxx'
SICOOBPIX_CAMINHO_CERT_PUBLICO='./path/file.pem'
SICOOBPIX_SENHA_CERT_PUBLICO='xxx'
SICOOBPIX_CAMINHO_CERT_PRIVADO='./path/file.key'
SICOOBPIX_SENHA_CERT_PRIVADO='xxx'
```

Caso tenha dúvidas de como obter esses dados, consulte o site do Sicoob Developers através do link: https://developers.sicoob.com.br

### Exemplos de Uso

#### Criar cobrança imediata

```phpt
$scope = ['cob.read', 'cob.write']; //Veja a lista completa na documentação do Banco Central
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
$cob->criar($cobranca); //Como segundo parâmetro é possível informar o txId, caso contrário será gerado automaticamente pelo PSP
```

#### Consultar cobrança imediata

```phpt
$cob->consultar('xxxx'); // Para consultar cobrança pelo txId

$parametros = ['inicio' => '2021-09-01T01:00:00-03:00', 'fim' => '2021-09-10T01:00:00-03:00']; // Consulte o site do Banco Central para outras opções de filtros
$cob->consultar(null, $parametros);
```

#### Alterar/revisar cobrança imediata

```phpt
$cobranca = [
    'calendario' => [
        'expiracao' => 3600
    ],
    'devedor' => [
        'cpf' => '12345678911',
        'nome' => 'Fulano'
    ],
    'valor' => [
        'original' => '2.00'
    ],
    'chave' => 'teste@teste.com',
    'solicitacaoPagador' => 'mensagem pagador'
];

$cob->alterar($cobranca, 'xxxx');
```

#### Criar webhook

```phpt
$scope = ['webhook.read', 'webhook.write']; //Veja a lista completa na documentação do Banco Central
$psp = new Psp($scope);
$webhook = new \Elemke\SicoobPix\Webhook($psp);
$webhook->criar('teste@teste.com', 'https://www.teste.com');
```

#### Consultar webhook

```phpt
$webhook = new \Elemke\SicoobPix\Webhook($psp);
$webhook->consultar('teste@teste.com');
```

#### Deletar webhook

```phpt
$webhook = new \Elemke\SicoobPix\Webhook($psp);
$webhook->deletar('teste@teste.com');
```

#### Licença

MIT