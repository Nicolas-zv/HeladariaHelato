<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Spatie\Activitylog\Facades\Activity;

class LogSuccessfulLogin
{
    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $userModel = $event->user instanceof \Illuminate\Database\Eloquent\Model
            ? $event->user
            : null;

        // Crear actividad de inicio de sesión
        activity()
            ->causedBy($userModel)
            ->log('El usuario '.$userModel->name.' ha iniciado sesión.');
    }
}
