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

    protected function startup()
    {
        parent::startup();
        if(!$this->attributeManager->exist()){
            $this->flashMessage('You need attributes first', 'alert-danger');
            $this->redirect('Attribute:default');
        }
    }

    public function renderDefault(){
        $this->template->items = $this->userManager->exist() ? $this->userManager->getContent() : null;
        $this->template->attributes = $this->attributeManager->exist() ? $this->attributeManager->getArrayContent() : null;
    }

    public function renderAdd(){

    }

    public function renderEdit($id){
        $item = $this->userManager->get($id);
    }

    public function handleDelete($id){

    }

    protected function createComponentUserForm(): Form
    {
        return $this->userForm->create();
    }
}
