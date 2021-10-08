<?php

  namespace Ewallet;

  use SoapServer;

  require 'vendor/autoload.php';

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

  $server = new SoapServer( 'specification/ProviderWebService.wsdl', $soapServerOptions );

  $server->setClass( Wallet::class );
  $server->handle();