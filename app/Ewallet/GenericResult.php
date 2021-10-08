<?php

  namespace Ewallet;

  use DateTime;

  class GenericResult {
    public string $errorMsg = '';
    public int    $status   = 0;
    public string $timeStamp;

    public function __construct() {
      $this->timeStamp = ( new DateTime( 'now' ) )->format( DATE_ATOM );
    }
  }