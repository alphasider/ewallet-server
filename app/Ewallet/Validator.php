<?php

  namespace Ewallet;

  use Dotenv\Dotenv;
  use GuzzleHttp\Client as HTTP_CLIENT;
  use GuzzleHttp\Exception\GuzzleException;
  use PDOException;

  class Validator {
    private function getDotEnvVars( Dotenv $dotenv ) {
      $dotenv::createImmutable( $_SERVER[ 'DOCUMENT_ROOT' ] )->load();
    }

    /**
     * @throws GuzzleException
     */
    public static function validatePhoneNumber( string $phoneNumber ) {
      $client        = new HTTP_CLIENT( [ 'base_uri' => $_ENV[ 'PHONE_VALIDATION_API' ] ] );
      $requestParams = "?api_key={$_ENV['PHONE_VALIDATION_API_KEY']}&phone=$phoneNumber";
      $client->request( 'GET', $requestParams );
    }

    public static function validateUser( string $username, string $password ): array {
      $username = filter_var( $username, FILTER_SANITIZE_STRING );
      $password = filter_var( $password, FILTER_SANITIZE_STRING );

      $queryToCheck = "SELECT * FROM clients WHERE username = :username AND password = :password";
      $stmt         = ( new Database )->connect()->prepare( $queryToCheck );
      $stmt->bindParam( ':username', $username );
      $stmt->bindParam( ':password', $password );
      $result = [];

      try {
        $stmt->execute();
        $result = $stmt->fetch( ARRAY_FILTER_USE_KEY );
      } catch ( PDOException $exception ) {
        echo $exception->getMessage();
      }
      if ( !is_array( $result ) || 0 >= count( $result ) ) {
        return [
          'status'  => 'failed',
          'message' => 'Неверное имя пользователя или пароль',
        ];
      }

      return [ 'status' => 'succeed', 'payload' => $result ];
    }
  }