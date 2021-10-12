<?php

  namespace Ewallet;

  class PerformTransactionResult extends GenericResult {
    public array $parameters;
    public int   $providerTrnId = 0;
  }