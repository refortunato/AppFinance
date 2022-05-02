
# Api de simulação de transações bancárias

Esta API foi desenvolvida com o intuito de simular transações bancárias entre usuários, procurando utilizar o padrão arquitetural da Clean Architecture (https://blog.cleancoder.com/uncle-bob/2012/08/13/the-clean-architecture.html).

![Clean Architecture](https://blog.cleancoder.com/uncle-bob/images/2012-08-13-the-clean-architecture/CleanArchitecture.jpg)
## Rodando localmente

Faça um clone do repostório da aplicação em sua máquina

Abra o terminal de sua preferência e execute o comando abaixo.

```bash
  git clone https://github.com/refortunato/AppFinance.git
```

Caso não tenha o docker instalado, neste link é possivel encontrar a opção para fazer download para desktop para seu respctivo sistema operacional. https://docs.docker.com/desktop/


Após baixar o repostório, entre no diretório da aplicação e execute o comando abaixo para o docker-compose subir os containers.


```bash
  docker-composer up --build
```

Isso deve levar alguns segundos. Caso ocorra algum erro, certifique-se de que as portas 8888 e 3309 não estejam ocupadas com alguma aplicação.
    
Execute o comando abaixo dentro do diretório da aplicação para o composer baixar as dependências.
```bash
  composer install
```

Importe o arquivo **database.sql** que está na raíz do projeto para seu banco de dados MySql, isso fará com que as tabelas sejam criadas.
Você poderá fazer isso utilizando PHPMyAdmin ou MySqlWorkbench (https://dev.mysql.com/downloads/workbench/).

As strings de conexão com o banco de dados podem ser encontradas no script */src/Config/database.php* ou no aquivo *docker-compose.yml*.

Caso queira acessar através do host, você pode utilizar as strings abaixo:

**SERVER:** localhost 

**PORT:** 3309

**USER:** finance

**PASSWORD:** finance010203

## Documentação da API

Tendo em vista que os containers já estejam rodando corretamente, a url abaixo o direcionará para a API :

```http
http://localhost:8888
```

## Resumo

Básicamente, para conseguirmos fazer uso da API, precisamos seguir o seguinte fluxo:
- Criar usuário(s) comum  (pois são os únicos que poderão realizar transferências).
- (Opcional) Criar usuários lojistas.
- Realizar Login para obter o token de autenticação.
- Consumir EndPoint para realizar transferência.
- Visualizar transferências relacionadas aos usuários (tanto na origem quanto no destino).

Logo abaixo, estão as descrições dos EndPoints disponíveis para o consumo da API.

### Criar usuário comum

```http
  POST /common-user
```
Enviar uma requisição com os campos abaixo formatados em JSON.


| Parâmetro   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `name` | `string` | **Obrigatório**. Nome do usuário |
| `cpf_cnpj` | `string` | **Obrigatório**. CPF/CNPJ do usuário|
| `email` | `string` | **Obrigatório**. E-mail do usuário|
| `password` | `string` | **Obrigatório**. Senha do usuário (min. de 8 caracteres)|
| `repeat_password` | `string` | **Obrigatório**. Senha do usuário (min. de 8 caracteres)|

#### Retorno
| Campo   | Descrição                           |
| :---------- | :---------------------------------- |
| `user_id` |  Id do usuário |
| `user_name` |  Nome do usuário |
| `cpf` | CPF do usuário|
| `cnpj` | CPF do usuário|
| `email` | E-mail do usuário|



### Criar usuário lojista

```http
  POST /store-user
```
Enviar uma requisição com os campos abaixo formatados em JSON.


| Parâmetro   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `name` | `string` | **Obrigatório**. Nome do usuário |
| `cpf_cnpj` | `string` | **Obrigatório**. CPF/CNPJ do usuário|
| `email` | `string` | **Obrigatório**. E-mail do usuário|
| `password` | `string` | **Obrigatório**. Senha do usuário (min. de 8 caracteres)|
| `repeat_password` | `string` | **Obrigatório**. Senha do usuário (min. de 8 caracteres)|

#### Retorno
| Campo   | Descrição                           |
| :---------- | :---------------------------------- |
| `user_id` |  Id do usuário |
| `user_name` |  Nome do usuário |
| `cpf` | CPF do usuário|
| `cnpj` | CPF do usuário|
| `email` | E-mail do usuário|


### Obter token para autenticação

```http
  POST /login
```
Enviar uma requisição com os campos abaixo formatados em JSON.


| Parâmetro   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `email` | `string` | **Obrigatório**. E-mail do usuário |
| `password` | `string` | **Obrigatório**. Senha do usuário |

#### Retorno
| Campo   | Descrição                           |
| :---------- | :---------------------------------- |
| `token` |  JWT para autenticação |


### Realizar transferência entre contas

EndPoint onde usuários comuns podem realizar transferência para outros usuários comuns ou lojistas.

Usuário lojista não poderá efeturar transferência.

```http
  POST /transfer
```
Deve ser informado o token obtido ne endPoint de **login**, no header da requisição.
```http
  Authorization: Bearer <token>
```

Enviar uma requisição com os campos abaixo formatados em JSON.


| Parâmetro   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `destiny_account_id` | `string` | **Obrigatório**. ID do usuário destino. |
| `value` | `float` | **Obrigatório**. Valor a ser transferidos. |

#### Retorno
| Campo   | Descrição                           |
| :---------- | :---------------------------------- |
| `id` | ID da transação |
| `run_date` | Data da transação |
| `origin_account_id` | ID do usuário onde foi subtraído o valor para efeturar a transação |
| `destiny_account_id` | ID do usuário para onde o valor foi transferido |
| `transaction_type` | Tipo da transação |
| `origin_transaction_id` | ID da transação que originou a transação atual (campo utilizado para quando a transação for um estorno). Caso não haja, será retornado `null` |
| `value` | Valor da transação |


### Obter lista de transferências

EndPoint para obter todas as transações realcionadas a um usuário.


```http
  GET /account-transactions
```
Deve ser informado o token obtido ne endPoint de **login**, no header da requisição.
```http
  Authorization: Bearer <token>
```
#### Retorno
Será retornada uma lista com os seguintes campos:
| Campo   | Descrição                           |
| :---------- | :---------------------------------- |
| `id` | ID da transação |
| `run_date` | Data da transação |
| `origin_account_id` | ID do usuário onde foi subtraído o valor para efeturar a transação |
| `destiny_account_id` | ID do usuário para onde o valor foi transferido |
| `transaction_type` | Tipo da transação |
| `origin_transaction_id` | ID da transação que originou a transação atual (campo utilizado para quando a transação for um estorno). Caso não haja, será retornado `null` |
| `value` | Valor da transação |

----

## Stack utilizada

**Back-end:** PHP8.1, Nginx

**Frameworks:** Slim4 (https://www.slimframework.com/docs/v4/)

**Banco de dados:** MySql

