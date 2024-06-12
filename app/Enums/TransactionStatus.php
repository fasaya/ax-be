<?php

namespace App\Enums;

enum TransactionStatus: string
{
    case Paid = 'paid';
    case Pending = 'pending';
    case Cancelled = 'cancelled';
}
