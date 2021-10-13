<?php
  declare( strict_types = 1 );

  namespace Ewallet;

  use Dotenv\Dotenv;
  use SoapServer;

  require 'vendor/autoload.php';

  $dotenv = Dotenv::createMutable( __DIR__ );
  $dotenv->load();
  $wsdlFile = $_ENV[ 'WSDL_FILE' ];

  $soapServerOptions = [
    'classmap' => [
      'GenericParam'                => GenericParam::class,
      'GenericResult'               => GenericResult::class,
      'GenericArguments'            => GenericArguments::class,
      'GetInformationResult'        => GetInformationResult::class,
      'GetInformationArguments'     => GetInformationArguments::class,
      'PerformTransactionArguments' => PerformTransactionArguments::class,
    ],
  ];

  $server = new SoapServer( $wsdlFile, $soapServerOptions );

  $server->setClass( Wallet::class );
  $server->handle();