<?php

namespace App\Console\Commands;

use App\Events\InstallmentHasBeenDeferred;
use App\Models\Installment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckInstallments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'installment:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now()->toDateString();
        $installments = Installment::select(['id', 'date_of_payment'])
            ->whereDate('date_of_payment', '<=', $now)->get();

        foreach ($installments as $installment) {
            Installment::where('id', $installment->id)->update(
                [
                    'status' => 'Deferred_installments'
                ]
            );
            event(new InstallmentHasBeenDeferred($installment));
        }
    }
}
