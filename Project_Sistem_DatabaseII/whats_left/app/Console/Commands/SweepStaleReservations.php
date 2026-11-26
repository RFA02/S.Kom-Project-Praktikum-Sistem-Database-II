<?php

namespace App\Console\Commands;

use App\Services\StaleReservationSweeper;
use Illuminate\Console\Command;

class SweepStaleReservations extends Command
{
    protected $signature = 'whatsleft:sweep-stale';

    protected $description = 'Kedaluwarsakan listing & selesaikan reservasi yang jam pickup-nya sudah lewat';

    public function handle(StaleReservationSweeper $sweeper): int
    {
        $result = $sweeper->sweep();

        $this->info(sprintf(
            'Listing kedaluwarsa: %d, reservasi dibatalkan otomatis: %d, ditandai tidak hadir: %d',
            $result['listings'],
            $result['cancelled'],
            $result['no_show']
        ));

        return self::SUCCESS;
    }
}
