<?php

  use Doctrine\ORM\Tools\Setup;
  use Doctrine\ORM\EntityManager;
  date_default_timezone_set('America/Lima');
  require_once "../vendor/autoload.php";
  $isDevMode = true;
  $config = Setup::createYAMLMetadataConfiguration(array(__DIR__ . "/config/yaml"), $isDevMode);
  $conn = array(
    'host' => 'ec2-54-155-226-153.eu-west-1.compute.amazonaws.com',
    'driver' => 'pdo_pgsql',
    'user' => 'pzsfgbzsnlihxa',
    'password' => '86a2b06b4c10d2836bea7ea8744bd6dc8b2d362a1afd03322d8f45554177962c',
    'dbname' => 'deisa7op8lrfko',
    'port' => '5432'
  );
  $entityManager = EntityManager::create($conn, $config);

?>
