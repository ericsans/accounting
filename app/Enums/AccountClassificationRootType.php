<?php

namespace App\Enums;

enum AccountClassificationRootType: int {
    /**
     * Indicates that the type of data are part of the assets from account classification account types.
     *
     * @var integer
     */
    case Assets = 1;

    /**
     * Indicates that the type of data are part of the liabilities from account classification account types.
     *
     * @var integer
     */
    case Liabilities = 2;

    /**
     * Indicates that the type of data are part of the equity from account classification account types.
     *
     * @var integer
     */
    case Equity = 3;

    /**
     * Indicates that the type of data are part of the revenue from account classification account types.
     *
     * @var integer
     */
    case Revenue = 4;

    /**
     * Indicates that the type of data are part of the expenses from account classification account types.
     *
     * @var integer
     */
    case Expenses = 5;
}
