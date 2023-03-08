starkapi-lumen
Simple API application , using StarkAPI Bank


• app : aplicação , todo o projeto do Lumen .
• config : dentro há uma pasta de configuração do arquivo default do apache ( 000-default.conf ) , fazendo com que nossa API execute em Https . o https é necessário pois o webhook exige um URI Https , para isso gerei para um certificado Let’s Encrypt ( Linux + Certbot + ACME DNS Challenge ) sem ter custo com aquisição de cerificados para proteger a conexão , sem precisar utilizar um certificado self-signed.

###########################################################

inicializando as migrations : acesse seu container , exemplo : ‘docker exec -it id_container bash ‘ . navegue ate o diretório “/var/www/html” e execute o comando “php artisan migrate”.

Arquitetura da API . Foi feito a criação de 2 ServicesProviders : 

• StarkBankProvider : responsável por registrar o serviço da StarkBank em um modo centralizado , permitindo que de forma simples possamos alterar ambiente de trabalho ou comunicação com a API da StarkBank

Como pode observar , estamos obtendo os dados da chave que está guardada dentro da pasta “keys” , feito isso , consigo agora definir como modo Padrão o tipo de autenticação da API e projeto ser utilizado , assim como o ambiente ‘sandbox ‘.

• Serviço de S3 , onde através desse ServiceProvider , consigo definir minha região , chave , segredo sem ter que me preocupar em referenciar em múltiplos controllers/locais.


