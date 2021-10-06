<?php

  namespace Ewallet;

  use DateTime;

  class GenericResult {
    public string   $errorMsg;
    public int      $status;
    public DateTime $timeStamp;
  }