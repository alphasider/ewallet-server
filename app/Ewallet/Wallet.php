<?php

  namespace Ewallet;

  use DateTime;

  class Wallet {

    public function PerformTransaction( PerformTransactionArguments $params ): PerformTransactionArguments {
      $params->username = "Username $params->username has been changed to Genius";

      return $params;
    }

    public function GetInformation( GetInformationArguments $params ): GetInformationResult {
      $result             = new GetInformationResult();
      $result->parameters = $params->parameters;

      return $result;
    }
  }