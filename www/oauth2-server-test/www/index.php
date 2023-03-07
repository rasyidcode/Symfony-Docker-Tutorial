<?php

require_once('../vendor/autoload.php');

class App
{

    private static ?self $instance = null;

    private OAuth2\Server $server;

    private function __construct()
    {
        $dsn    = 'mysql:dbname=oauth2_server_db;host=localhost';
        $user   = 'root';
        $pass   = '';

        ini_set('display_errors', 1);
        error_reporting(E_ALL);

        OAuth2\Autoloader::register();

        $storage = new OAuth2\Storage\Pdo(['dsn' => $dsn, 'username' => $user, 'password' => $pass]);

        $this->server = new OAuth2\Server($storage);
        $this->server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));
        $this->server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function run(): void
    {
        $requestPath = $this->getRequestPath();
        switch ($requestPath) {
            case '/':
                $this->indexAction();
                break;
            case '/token':
                $this->tokenAction();
                break;
            case '/resource':
                $this->resourceAction();
                break;
            case '/authorize':
                $this->authorizeAction();
                break;
        }
    }

    private function getRequestPath(): string
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        return parse_url($requestUri, PHP_URL_PATH);
    }

    private function indexAction(): void
    {
        echo 'Welcome to OAuth2 Server Test';
    }

    private function tokenAction(): void
    {
        $this->server->handleTokenRequest(OAuth2\Request::createFromGlobals())->send();
    }

    private function resourceAction(): void
    {
        $isValid = $this->server->verifyResourceRequest(OAuth2\Request::createFromGlobals());
        if (!$isValid) {
            $this->server->getResponse()->send();
            die;
        }

        echo json_encode(['success' => true, 'message' => 'You accessed my APIs!']);
    }

    private function authorizeAction(): void
    {
        $request = OAuth2\Request::createFromGlobals();
        $response = new OAuth2\Response();

        $isValid = $this->server->validateAuthorizeRequest($request, $response);
        if (!$isValid) {
            $response->send();
            die;
        }

        if (empty($_POST)) {
            exit('<form method="post">
                    <label>Do You Authorize TestClient?</label><br />
                    <input type="submit" name="authorized" value="yes">
                    <input type="submit" name="authorized" value="no">
                  </form>');
        }

        $isAuthorized = ($_POST['authorized'] === 'yes');
        $this->server->handleAuthorizeRequest($request, $response, $isAuthorized);
        if ($isAuthorized) {
            $code = substr($response->getHttpHeader('Location'), strpos($response->getHttpHeader('Location'), 'code=')+5, 40);
            exit('SUCCESS! Authorization Code: ' . $code);
        }

        $response->send();
    }

}

App::getInstance()->run();