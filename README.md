# VCWeb Networks | Skeleton

## Instalação

Para realizar a instalação do framework e seus respectivos arquivos basta ir ao seu terminal
da sua escolhar e acessar sua pasta aonde fica armazenados seus **HOSTS** e executar o comando abaixo.

````bash
composer create-project --prefer-dist navegarte/framework NOME_DA_PASTA
````

## Configuração do site

As configurações ficam em `config/*.php` e `.env` para dados sensíveis.

- `app.php` Configuração do sistema interno difícilmente irá mexer.
- `client.php` Configuração do cliente **importante configurar com os dados correto dele.**
- `database.php` Dados de conexão com o banco de dados que você irá configurar no arquivo `.env`.
- `mail.php` Dados de conexão com o servidor de e-mail e os alguns dados desse arquvio você configura em `.env`.
- `view.php` Configuração da view.

Você pode ficar livre para criar suas configurações e arquivos dentro dessa pasta, para usar no sistema.

## Configuração de pastas

A Configuração de pasta pode definir como será a estrutura delas em sua hospedagem.

A pasta `public` deve ser a raíz do seu site como a pasta `public_html` das hospedagem, 
é possível mudar a estrutura da pasta do framework, o arquivo referente a configuração da estrutura é
o `public/index.php` nele contém algumas **definições** para definir os diretórios.
Segue um exemplo:

````php
  /**
   * Root folder
   *
   * Defines where the public application files will be kept
   */
  define('PUBLIC_FOLDER', ROOT . '/public');
  
  
  /**
   * Application folder
   *
   * Defines where protected application files will be kept
   */
  define('APP_FOLDER', ROOT);
````

A definição **PUBLIC_FOLDER** serve para falar qual diretório e a raíz do projeto.
No caso de uma hospedagem eu sempre prefiro por `define('PUBLIC_FOLDER', ROOT . '/public_html');` até
porque é a raíz da hospedagem, agora a outra definição `define('APP_FOLDER', ROOT);` eu sempre 
ponho como `define('APP_FOLDER', ROOT . '/application');` e todos os outros arquivos eu coloco dentro
dela e a estrutura ficaria assim:

- application
- public_html

Na pasta `public_html` fica apenas o `index.php` e os `assets, images, javasripts, css` e oque você querer
adicionar para ser visivél pelo navegador, a pasta `application` ela fica fora por segurança dos arquivos
pois ninguém além de você desenvolvedor terá acesso a ela.

## Como criar uma rota, controller, view

Caso já tenha mudado a estrutura da pasta do framework ao seu agrado de desenvolvimento procure todos
caminhos que irei falar dentro da definição que você definiu do **APP_FOLDER.**

#### Criar rota WEB

Para criar uma rota na web acesse `APP_FOLDER/routes/web.php`. 

Rotas para web existe várias forma de criação, entretando eu criei um método para simplificar nossas vidas.

````php
  $method = 'get'; // Existe vários métodos mais os usados são (get e post)
  $pattern = '/minharota'; // URL que será acessado no navegador
  $controller = 'MinhaRotaController'; // Nome do controller
  $name = 'minharota'; // Nome da rota. OBS: Nunca pode repetir esse nome, são chaves únicas.
  $middleware = null; // Middleware que caso tenha será executado antes de abrir a rota.
  
  $app->route($method, $pattern, $controller, $name, $middleware);
````

- O `$method` pode ser usado em conjunto como `get,post` que será permitido acesso como GET e POST na rota.
- O `$controller` sempre ficará armazenado em `APP_FOLDER/app/Controllers/NOME_DO_CONTROLLER`

Caso queira ver as outras opções de criação de rotas acesse a **[Documentação Oficial do Slim3](https://www.slimframework.com/docs/objects/router.html)**

#### Criar rota API

As rotas para api segue o mesmo padrão da normal, o único diferencial é que elas começa com **/api** no começo de cada `$pattern`.

#### Criar controller

Todos controller seguem o mesmo padrão como:

````php
  namespace App\Controllers {
  
      use Core\Contracts\Controller;
  
      /**
       * Class HomeController
       */
      final class MinhaRotaController extends Controller
      {
          /**
           * Inicializa junto com o Controller
           */
          public function boot()
          {
              // Esse iniciza no __contruct da classe pai.
              // Serve para você pode adicionar suas propriedades ou fazer
              // alguma verificação antes de ir para os métodos da classe.
          }
  
          /**
           * View /
           *
           * @return mixed
           */
          public function get()
          {
              $array = [];
  
              return $this->view('home', $array, 200);
          }
      }
  }
````

Repare no `public function get()` e na rota que criamos colocamos o `$method` como `get` como a rota é `get` e não passamos 
mais nenhuma ação a ela, no controller ela é chamada com o `public function get()` e caso a gente ponhe o `$method` como `get,post`
e você envia um post para essa rota do `controller` é preciso ter outro método na classe para receber a requisição `post`

````php
  /**
   * POST /
   *
   * @return mixed
   */
  public function post()
  {
      $post = $this->param();
  
      if (!empty($post)) {
          // Faça sua lógica
      }
  }
````

Podemos também passar ações ao controller `MinhaRotaController@action` o `@` serve para separar a ação e o `action` e o nome da ação
e para isso funcionar você deve adicionar essa ação ao controller. 

##### Rota

````php
  $method = 'get'; // Existe vários métodos mais os usados são (get e post)
  $pattern = '/minharota/action'; // URL que será acessado no navegador
  $controller = 'MinhaRotaController@action'; // Nome do controller
  $name = 'minharota.action'; // Nome da rota. OBS: Nunca pode repetir esse nome, são chaves únicas.
  $middleware = null; // Middleware que caso tenha será executado antes de abrir a rota.
  
  $app->route($method, $pattern, $controller, $name, $middleware);
````

##### Controller

````php
  /**
   * View /minharota/action
   *
   * @return mixed
   */
  public function getAction()
  {
      //
  }
````

Caso o `$method` seja `get,post`

````php
  /**
   * View /minharota/action
   *
   * @return mixed
   */
  public function getAction()
  {
      //
  }
  
  /**
   * POST /minharota/action
   *
   * @return mixed
   */
  public function postAction()
  {
      //
  }
````

##### Views

Repare no Controller que criação lá acima que o `public function get()` retorna um `$this->view($archive, $array, $status);` que por sua vez
irá retornar o HTML desenvolvido na view para o browser

````php
  $archive = 'home';
  $array = array('name' => 'Vagner Cardoso');
  $status = 200';
  
  $this->view($archive, $array, $status);
````

- `$archive` é o nome do arquivo de template que fica localizado dentro da pasta `APP_FOLDER/resources/view` todos arquivos de template fica dentro dela.
- `$array` é oque você ira retornar de dados para a view poder polular e printar eles na tela do browser.
- `$status` é o status code do retorno.

E dentro das views é **HTML** normal, porém utilizamos um sistema de template chamado [Twig](https://twig.symfony.com/) para não misturarmos php
com html, pois as boas pratica de desenvolvimento não utilizamos para não injeçar nosso código fonte e ficar mais bonito.

Para você dar seus `foreach, if, etc` no twig é muito simples é só olhar na [Documentação](https://twig.symfony.com/doc/2.x/) pois existe diversos
filtros, funções e possibilidades para usarmos na view.

> Restante da documentação está sendo criada, aguarde...
