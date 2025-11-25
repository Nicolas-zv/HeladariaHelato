<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Spatie\Activitylog\Facades\Activity;

class LogSuccessfulLogout
{
    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Logout  $event
     * @return void
     */
    public function handle(Logout $event)
    {
        $userModel = $event->user instanceof \Illuminate\Database\Eloquent\Model
            ? $event->user
            : null;

        // Crear actividad de cierre de sesión
        activity()
            ->causedBy($userModel)
            ->log('El usuario '.$userModel->name.' ha cerrado sesión.');
    }
}
