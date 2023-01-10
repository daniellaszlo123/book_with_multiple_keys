<?php

namespace App\Observers;

use App\Models\Lending;
use Illuminate\Support\Facades\DB;

class LendingObserver
{
    /**
     * Handle the Lending "created" event.
     *
     * @param  \App\Models\Lending  $lending
     * @return void
     */
    public function created(Lending $lending)
    {
        DB::table('copies')->where('copy_id', $lending->copy_id)->update(['status'=>1]);
    }

    /**
     * Handle the Lending "updated" event.
     *
     * @param  \App\Models\Lending  $lending
     * @return void
     */
    public function updated(Lending $lending)
    {
        //
    }

    /**
     * Handle the Lending "deleted" event.
     *
     * @param  \App\Models\Lending  $lending
     * @return void
     */
    public function deleted(Lending $lending)
    {
        //
    }

    /**
     * Handle the Lending "restored" event.
     *
     * @param  \App\Models\Lending  $lending
     * @return void
     */
    public function restored(Lending $lending)
    {
        //
    }

    /**
     * Handle the Lending "force deleted" event.
     *
     * @param  \App\Models\Lending  $lending
     * @return void
     */
    public function forceDeleted(Lending $lending)
    {
        //
    }
}
