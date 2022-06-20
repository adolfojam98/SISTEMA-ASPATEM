<?php

namespace App\Console\Commands;

use App\Http\Services\CuotaService;
use Illuminate\Console\Command;

class UpdateLatePayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cuota:UpdateLatePayment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualizamo los pagos atrasados';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $service = new CuotaService();
        $service->updateLatePayment();
        return 1;
    }
}
