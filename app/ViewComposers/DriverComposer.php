<?php

namespace App\ViewComposers;

use App\Repositories\CategoryRepository;
use App\Appuser;
use Illuminate\View\View;

class DriverComposer
{  

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $countappUser = Appuser::where('document_verified',0)->whereIn("account_status",[2,3])->where('is_read',0)->orderBy('id','desc')->get()->toArray();
        $view->with('countappUser', $countappUser);
    }
}