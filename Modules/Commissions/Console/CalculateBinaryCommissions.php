<?php

namespace Modules\Commissions\Console;

use Illuminate\Console\Command;
use Modules\Commissions\App\Services\BinaryCommissionService;



class CalculateBinaryCommissions extends Command
{
    protected $signature = 'commissions:binary';
    protected $description = 'Calculate binary commissions weekly';

    public function handle(BinaryCommissionService $service)
    {
        $service->calculateBinaryCommissions();
        $this->info('Binary commissions calculated successfully.');
    }
}
