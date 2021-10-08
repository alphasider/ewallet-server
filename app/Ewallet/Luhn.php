<?php

  namespace Ewallet;

  use Tdely\Luhn\Luhn as LuhnAlgorithm;

  require '../../vendor/autoload.php';

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
     * Used to generate unique ewallet number by client id
     *
     * @param int $clientId
     *
     * @return int
     */
    public static function generateEwalletNumber( int $clientId ): int {
      $eWalletNumber = self::$eWalletNumberPrefix . self::generateRandomNumberByUniqueId( $clientId );

      return LuhnAlgorithm::create( $eWalletNumber );
    }

    /**
     * Facade for original algorithm validation method
     *
     * @param int $ewalletNumber
     *
     * @return bool
     */
    public static function validateEwalletNumber( int $ewalletNumber ): bool {
      return LuhnAlgorithm::isValid( $ewalletNumber );
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
        // The "magic" numbers '1' and '9' are used to achieve something like this: rand(11111, 99999)
        $minRandomNumber .= '1';
        $maxRandomNumber .= '9';
        $randomNumbersLengthToGenerate--;
      }

      return rand( $minRandomNumber, $maxRandomNumber );
    }
  }
  var_dump(Luhn::validateEwalletNumber(999411712013591));