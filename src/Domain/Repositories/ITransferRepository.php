<?php

namespace AppFinance\Domain\Repositories;

use AppFinance\Domain\Entities\Transfer;

interface ITransferRepository
{
    public function save(Transfer $transfer): Transfer;
}