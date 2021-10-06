<?php

  namespace Ewallet;

  use DateTime;

  class Wallet {

    public function PerformTransaction( PerformTransactionArguments $params ): PerformTransactionArguments {
      $params->username = "Username $params->username has been changed to Genius";

      return $params;
    }

    public function GetInformation( $params ): GenericResult {
      $result            = new GenericResult();
      $result->errorMsg  = 'Classic error message';
      $result->status    = 404;
      $result->timeStamp = new DateTime( 'now' );

      return $result;
    }

  }