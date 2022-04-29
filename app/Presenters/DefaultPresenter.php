<?php

declare(strict_types=1);

namespace App\Presenters;


use App\Presenters\BasePresenter;


final class DefaultPresenter extends BasePresenter
{

    public function renderDefault(){
        $this->template->users = $this->userManager->getContentForChart();
    }

}
