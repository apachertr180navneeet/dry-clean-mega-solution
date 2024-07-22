<?php

namespace App\View\Composers;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;


class ViewComposer
{
    public function compose(View $view)
    {
        $view->with('userData',Auth::user());
    }
}
