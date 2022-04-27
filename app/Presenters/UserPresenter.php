<?php

declare(strict_types=1);

namespace App\Presenters;


use App\Forms\UserForm;
use App\Presenters\BasePresenter;
use Nette;
use Nette\Application\UI\Form;
use Nette\DI\Attributes\Inject;


final class UserPresenter extends BasePresenter
{
    #[Inject]
    public UserForm $userForm;

    public function renderDefault(){

    }

    public function renderAdd(){

    }

    public function renderEdit($id){

    }

    public function handleDelete($id){

    }

    protected function createComponentUserForm(): Form
    {
        return $this->userForm->create();
    }
}
