<?php

  use Psr\Http\Message\ResponseInterface as Response;
  use Psr\Http\Message\ServerRequestInterface as Request;

  use Slim\Factory\AppFactory;

  use Tuupola\Middleware\HttpBasicAuthentication;
  use \Firebase\JWT\JWT;

  require __DIR__ . '/vendor/autoload.php';
  require_once __DIR__ . '/bootstrap.php';

  $app = AppFactory::create();

  const JWT_SECRET = "makey1234567";


  function addCorsHeaders(Response $response): Response {
      $response = $response
      ->withHeader("Access-Control-Allow-Origin", 'http://localhost')
      ->withHeader("Access-Control-Allow-Headers", 'Content-Type, Authorization')
      ->withHeader("Access-Control-Allow-Methods", 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
      ->withHeader("Access-Control-Expose-Headers", "Authorization");

      return $response;
  }


  // Middleware de validation du Jwt
  $options = [
      "attribute" => "token",
      "header" => "Authorization",
      "regexp" => "/Bearer\s+(.*)$/i",
      "secure" => false,
      "algorithm" => ["HS256"],
      "secret" => JWT_SECRET,
      "path" => ["/api"],
      "ignore" => [
        "/hello",
        "/api/hello",
        "/api/login",
        "/api/createUser",
        "/api/catalogue"
      ],
      "error" => function ($response, $arguments) {
          $data = array('ERREUR' => 'Connexion', 'ERREUR' => 'JWT Non valide');
          $response = $response->withStatus(401);
          return $response->withHeader("Content-Type", "application/json")->getBody()->write(json_encode($data));
      }
  ];


  $app->post('/api/login', function (Request $request, Response $response, $args) {
      $issuedAt = time();
      $expirationTime = $issuedAt + 60; // en secondes
      $payload = array(
          'userid' => "12345",
          'email' => "emmanuel.maurice@gmail.com",
          'pseudo' => "emma",
          'iat' => $issuedAt,
          'exp' => $expirationTime
      );

      $token_jwt = JWT::encode($payload,JWT_SECRET, "HS256");
      $response = $response->withHeader("Authorization", "Bearer {$token_jwt}");

      addCorsHeaders($response);
      return $response;
  });


  $app->get('/api/catalogue', function (Request $request, Response $response, $args) {
      // $flux = '[
      //   {
      //     ref: "Y2LWP95M",
      //     name: "Linux",
      //     price: 10
      //   },
      //   {
      //     ref: "M75CEPTK",
      //     name: "Windows",
      //     price: 15
      //   },
      //   {
      //     ref: "75FGMDCX",
      //     name: "Angular",
      //     price: 5
      //   },
      //   {
      //     ref: "SX9BG46C",
      //     name: "Talend",
      //     price: 0
      //   },
      // ]';

    global $entityManager;

    $articlesRepository = $entityManager->getRepository('Articles');
    $articles = $articlesRepository->findAll();

    $data = [];

    foreach ($articles as $article) {
      $elem = [];
      $elem["ref"] = $article->getRef();
      $elem["name"] = $article->getName();
      $elem["price"] = $article->getPrice();

      array_push($data, $elem);
    }

    $response = $response->withHeader("Content-Type", "application/json;charset=utf-8");

    $response->getBody()->write(json_encode($data));

    addCorsHeaders($response);
    return $response;
  });


  $app->get('/api/client/{id}', function (Request $request, Response $response, $args) {
      $array = [];
      $array ["nom"] = "maurice";
      $array ["prenom"] = "emmanuel";

      $response->getBody()->write(json_encode ($array));

      addCorsHeaders($response);
      return $response;
  });


  $app->get('/hello/{name}', function (Request $request, Response $response, $args) {
      $array = [];
      $array ["nom"] = $args ['name'];
      $response->getBody()->write(json_encode ($array));

      addCorsHeaders($response);
      return $response;
  });


  $app->get('/api/hello/{name}', function (Request $request, Response $response, $args) {
      $array = [];
      $array ["nom"] = $args ['name'];
      $response->getBody()->write(json_encode ($array));

      addCorsHeaders($response);
      return $response;
  });


  // Chargement du Middleware
  $app->add(new Tuupola\Middleware\JwtAuthentication($options));

  $app->run ();
