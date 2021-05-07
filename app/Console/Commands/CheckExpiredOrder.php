<?php

namespace App\Console\Commands;

use App\Repositories\OrderRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckExpiredOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'atoz:checkexpiredorder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Expired Payment Order';

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
        $orderRepo = new OrderRepository();
        $listUnpaid = $orderRepo->findAllUnpaid();

        if ($listUnpaid->count() > 0) {
            foreach ($listUnpaid as $value) {
                if (Carbon::now()->greaterThan($value->expired_at)) {
                    $orderRepo->cancelOrder($value->id, $value->created_by);
                }
            }
        }

        return 'Order has been checked';
    }
}
