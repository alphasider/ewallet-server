<?php

  namespace Ewallet;

  use Tdely\Luhn\Luhn as LuhnAlgorithm;

  require "{$_SERVER['DOCUMENT_ROOT']}/vendor/autoload.php";

  class Luhn {
    /**
     * @var int
     */
    private static int $eWalletNumberPrefix = 999;

    /**
     * @var int
     */
    private static int $eWalletNumberLengthWithoutPrefix = 13;

    /**
     * Used on generate checksum
     *
     * @var int
     */
    private static int $controlNumberLength = 1;

    /**
     * Used to generate unique wallet number by client id
     *
     * @param int $clientId
     *
     * @return int
     */
    public static function generateWalletNumber( int $clientId ): int {
      $eWalletNumber = self::$eWalletNumberPrefix . self::generateRandomNumberByUniqueId( $clientId );

      return LuhnAlgorithm::create( $eWalletNumber );
    }

    /**
     * Facade for original algorithm validation method
     *
     * @param int $walletNumber
     *
     * @return bool
     */
    public static function validateWalletNumber( int $walletNumber ): bool {
      return LuhnAlgorithm::isValid( $walletNumber );
    }

    /**
     * Used to generate random number (with fixed length)
     * TODO: this method requires some refactoring
     *
     * @param $uniqueId
     *
     * @return int
     */
    private static function generateRandomNumberByUniqueId( $uniqueId ): int {
      $uniqueIdLength                = strlen( (string) $uniqueId );
      $randomNumbersLengthToGenerate = self::$eWalletNumberLengthWithoutPrefix - $uniqueIdLength - self::$controlNumberLength;
      $minRandomNumber               = '';
      $maxRandomNumber               = '';

      while ( $randomNumbersLengthToGenerate > 0 ) {
        // The "magic" numbers '1' and '9' are used to achieve fixed length of min and max values something like this: rand(11111, 99999)
        $minRandomNumber .= '1';
        $maxRandomNumber .= '9';
        $randomNumbersLengthToGenerate--;
      }

      return rand( $minRandomNumber, $maxRandomNumber );
    }
  }