<?php
  declare( strict_types = 1 );

  namespace Ewallet;

  use PDOException;

  require "{$_SERVER['DOCUMENT_ROOT']}/vendor/autoload.php";

  class Wallet {
    private int $depositLimit = 124499999;

    // TODO: Make stubs for other methods

    /**
     * FIXME: The method is not completed yet
     *
     * @param PerformTransactionArguments $params
     *
     * @return PerformTransactionResult
     */
    public function PerformTransaction( PerformTransactionArguments $params ): PerformTransactionResult {
      $result   = new PerformTransactionResult();
      $client   = new Client();
      $userData = $client->getClient( $params->username, $params->password );


      if ( is_string( $userData ) ) {
        $result->errorMsg = $userData;

        return $result;
      }

      $parameters   = $params->parameters;
      $walletNumber = '';
      $phoneNumber  = '';

      foreach ( $parameters as $parameter ) {
        $hasPhoneOrWalletNumber = property_exists( $parameter->paramKey, 'wallet_number' ) || property_exists( $parameter->paramKey, 'phone_number' );

        if ( !isset( $parameter->paramKey ) && !$hasPhoneOrWalletNumber ) {
          continue;
        }

        $walletNumber = 'wallet_number' === $parameter->paramKey ? $parameter->paramValue : 0;
        $phoneNumber  = 'phone_number' === $parameter->paramKey ? $parameter->paramValue : 1;
      }

      $isWalletValid = Validator::validateWalletNumber( (int) $walletNumber );

      if ( !$isWalletValid ) {
        $result->errorMsg = 'Ошибка в номере кошелька';

        return $result;
      }

      if ( $params->amount >= $this->depositLimit ) {
        $result->errorMsg = 'Лимит пополнения превышен!';

        return $result;
      }

      $queryToUpdateBalance = "UPDATE wallets SET balance = (balance + :amount) WHERE wallet_number = :wallet_number OR phone = :phone";
      $stmtToUpdateBalance  = ( new Database() )->connect()->prepare( $queryToUpdateBalance );
      $stmtToUpdateBalance->bindParam( ':amount', $params->amount );
      $stmtToUpdateBalance->bindParam( ':wallet_number', $walletNumber );
      $stmtToUpdateBalance->bindParam( ':phone', $phone );
      $affectedRows = 0;

      try {
        $stmtToUpdateBalance->execute();
        $affectedRows = $stmtToUpdateBalance->rowCount();
      } catch ( PDOException $exception ) {
        echo $exception->getMessage(); // FIXME: Handle this other way than just output
      }

      if ( 0 === $affectedRows ) {
        $result->errorMsg = "Не удалось выполнить операцию: $walletNumber, $phoneNumber";

        return $result;
      }

      $result->errorMsg      = 'Ok';
      $userUpdatedData       = $client->getClient( $params->username, $params->password );
      $result->parameters    = [
        new GenericParam( 'balance', $userUpdatedData[ 'payload' ][ 'balance' ] ),
        new GenericParam( 'depositLimit', $this->depositLimit ),
        new GenericParam( 'date', $result->timeStamp ),
      ];
      $result->providerTrnId = 72; // TODO: Implement transactions storing in DB

      return $result;
    }

    /**
     * Used to get user information
     *
     * @param GetInformationArguments $params
     *
     * @return GetInformationResult
     */
    public function GetInformation( GetInformationArguments $params ): GetInformationResult {
      $result   = new GetInformationResult();
      $userData = ( new Client() )->getClient( $params->username, $params->password );

      if ( is_string( $userData ) ) {
        $result->errorMsg = $userData;

        return $result;
      }

      $result->parameters = [
        new GenericParam( 'balance', $userData[ 'payload' ][ 'balance' ] ),
        new GenericParam( 'name', $userData[ 'payload' ][ 'name' ] ),
        new GenericParam( 'depositLimit', $this->depositLimit ),
      ];

      return $result;
    }
  }