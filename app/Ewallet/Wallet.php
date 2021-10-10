<?php
  declare( strict_types = 1 );

  namespace Ewallet;


  class Wallet {

    /**
     * FIXME: The method is not completed yet
     *
     * @param PerformTransactionArguments $params
     *
     * @return PerformTransactionArguments
     */
    public function PerformTransaction( PerformTransactionArguments $params ): PerformTransactionArguments {
      $params->username = "Username $params->username has been changed to Genius";

      return $params;
    }

    /**
     * Used to get user information
     *
     * @param GetInformationArguments $params
     *
     * @return GetInformationResult
     */
    public function GetInformation( GetInformationArguments $params ): GetInformationResult {
      $result             = new GetInformationResult();
      $result->parameters = $params->parameters;
      $userData           = ( new Client )->getClient( $params->username, $params->password );

      if ( is_string( $userData ) ) {
        $result->errorMsg = $userData;

        return $result;
      }

      $result->parameters = [
        new GenericParam( 'balance', $userData[ 'payload' ][ 'balance' ] ),
        new GenericParam( 'name', $userData[ 'payload' ][ 'name' ] ),
      ];

      return $result;
    }
  }