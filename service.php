<?php

  namespace Ewallet;

  use SoapServer;

  require 'vendor/autoload.php';

  $soapServerOptions = [
    'classmap' => [
      'PerformTransactionArguments' => PerformTransactionArguments::class,
      'GenericParam'                => GenericParam::class,
      'GenericResult'               => GenericResult::class,
    ],
  ];

  $server = new SoapServer( 'specification/ProviderWebService.wsdl', $soapServerOptions );

  $server->setClass( Wallet::class );
  $server->handle();