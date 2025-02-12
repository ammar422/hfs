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




// class CalculateBinaryCommissions extends Command
// {
//     /**
//      * The name and signature of the console command.
//      *
//      * @var string
//      */
//     protected $name = 'command:name';

//     /**
//      * The console command description.
//      *
//      * @var string
//      */
//     protected $description = 'Command description.';

//     /**
//      * Create a new command instance.
//      *
//      * @return void
//      */
//     public function __construct()
//     {
//         parent::__construct();
//     }

//     /**
//      * Execute the console command.
//      *
//      * @return mixed
//      */
//     public function handle()
//     {
//         //
//     }

//     /**
//      * Get the console command arguments.
//      *
//      * @return array
//      */
//     protected function getArguments()
//     {
//         return [
//             ['example', InputArgument::REQUIRED, 'An example argument.'],
//         ];
//     }

//     /**
//      * Get the console command options.
//      *
//      * @return array
//      */
//     protected function getOptions()
//     {
//         return [
//             ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
//         ];
//     }
// }
