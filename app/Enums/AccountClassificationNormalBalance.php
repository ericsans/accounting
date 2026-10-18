<?php

namespace App\Enums;

enum AccountClassificationNormalBalance: int {
    /**
     * Indicates that the type of data are part of the debit from account classification normal balances.
     *
     * @var integer
     */
    case Debit = 1;

    /**
     * Indicates that the type of data are part of the credit from account classification normal balances.
     *
     * @var integer
     */
    case Credit = 2;
}
