starkapi-lumen
Simple API application , using StarkAPI Bank

Dentro da pasta da api , haverá os seguintes diretórios com suas responsabilidades. 

• app : aplicação , todo o projeto do Lumen .
• config : dentro há uma pasta de configuração do arquivo default do apache ( 000-default.conf ) , fazendo com que nossa API execute em Https . o https é necessário pois o webhook exige um URI Https , para isso gerei para um certificado Let’s Encrypt ( Linux + Certbot + ACME DNS Challenge ) sem ter custo com aquisição de cerificados para proteger a conexão , sem precisar utilizar um certificado self-signed.

###########################################################

inicializando as migrations : acesse seu container , exemplo : ‘docker exec -it id_container bash ‘ . navegue ate o diretório “/var/www/html” e execute o comando “php artisan migrate”.

Arquitetura da API . Foi feito a criação de 2 ServicesProviders : 

• StarkBankProvider : responsável por registrar o serviço da StarkBank em um modo centralizado , permitindo que de forma simples possamos alterar ambiente de trabalho ou comunicação com a API da StarkBank

Como pode observar , estamos obtendo os dados da chave que está guardada dentro da pasta “keys” , feito isso , consigo agora definir como modo Padrão o tipo de autenticação da API e projeto ser utilizado , assim como o ambiente ‘sandbox ‘.

• Serviço de S3 , onde através desse ServiceProvider , consigo definir minha região , chave , segredo sem ter que me preocupar em referenciar em múltiplos controllers/locais.

##########################################################

Rotas

• $router->get('balance', 'BalanceController@getBalance'); responsável por exibir o atual saldo.

• $router->get('transfer', 'TransferController@transfer'); ( solicitado no teste ) : responsável por realizar a operação de transferência , onde espera receber como parametros os campos : banKCode , amount , Branch , account , taxId , name . caso algum desses parametros esteja faltando será retornado uma resposta Http 400.

• $router->get('transfersList', 'TransferController@transfersList'); responsável por exibir a listagem de transações realizadas em um determinado período , espera receber 2 parametros : dataInicial e dataFinal , caso algum desses campos esteja faltando será retornado uma resposta Http 400.

• $router->get('transferById/{id}', 'TransferController@transferById'); irá receber como argumento na rota o identificador da transferência , em seguida irá retornar os dados em JSON.

• $router->get('transfer/pdf/{id}', 'TransferController@getPDF'); Irá receber como argumento na rota o identificador da transferência , em seguida irá retornar o contente Raw o PDF.

• $router->get('invoice', 'InvoiceController@invoice'); rota criada no intuito de obter saldo dentro da aplicação , pois conforme a documentação explica , todas as invoices serão pagas em um ambiente sandbox , com isso foi possível realizar demais transações como transferências e outras . ( também poderia obter saldo através da operação de boletos ).

Essa rota espera receber como parametros , os seguintes campos : amount , vencimento ( Y-m-d ) , identificador ( CPF or CNPJ ) , nome .

• $router->get('invoiceById', 'InvoiceController@invoiceById'); espera receber como parâmetro , o id do invoice , com isso irá retornar em formato JSON os dados do invoice.

• $router->get('invoicesList', 'InvoiceController@invoicesList'); Deixei como default retornar os últimos 10 invoices realizados.

• $router->get('invoice/qr/{id}', 'InvoiceController@invoiceQR'); Espera receber como parâmetro o id do invoice , em seguida irá gerar uma imagem em formato .png dentro da /app/storage/ com o devido QRCODE.

• $router->get('boleto', 'BoletoController@boleto'); Espera receber como parametros : amount , nome , identificador , endereço , complemento , bairro , cidade , uf , cep e vencimento . com isso será gerado um Boleto.

• $router->get('boletoById', 'BoletoController@boletoById'); Esperar receber como parâmetro o id do Boleto , com isso irá retornar em formato JSON os dados do boleto.

• $router->get('boletosList', 'BoletoController@boletosList'); Espera receber como argumento na rota o id da transação de boleto , com isso irá gerar um PDF dentro da pasta /app/storage com um PDF contendo os dados do Boleto. • $router->get('registerHook','BaseController@registerHook'); Rota para teste / registrar um Webhook.

• $router->get(handleBucket, 'BaseController@handleBucket); Rota responsável por fazer com que , após obter os dados de histórico da transação , eu disparo um método GET através do Guzzle ( client http ) para um endpoint especifico em meu .env ( solicitado no teste ) . $router->get('transferHistory/{id}','TransferController@transferHistory'); Rota onde ao passar o id de uma transação como argumento para a rota , retorna o relacionamento que há entre essa transação e seu histórico ( HasMany Relation ).
