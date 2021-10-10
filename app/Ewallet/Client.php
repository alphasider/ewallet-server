<?php

  namespace Ewallet;

  class Client {

    /**
     * @param string $username
     * @param string $password
     *
     * @return array|mixed|string|string[]
     */
    public function getClient( string $username, string $password ) {
      $user        = Validator::validateUser( $username, $password );
      $isUserValid = 'succeed' === $user[ 'status' ];
      if ( !$isUserValid ) {
        return $user[ 'message' ];
      }

      return $user;
    }
  }