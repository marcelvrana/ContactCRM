<?php

declare(strict_types=1);

namespace App\Presenters;


use App\Forms\UserForm;
use App\Presenters\BasePresenter;
use Nette;
use Nette\Application\Attributes\Persistent;
use Nette\Application\UI\Form;
use Nette\DI\Attributes\Inject;


final class UserPresenter extends BasePresenter
{
    #[Persistent]
    public null|string $id = null;


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
        $this->id = null;
        $this->template->items = $this->userManager->exist() ? $this->userManager->getContent() : null;
        $this->template->attributes = $this->attributeManager->exist() ? $this->attributeManager->getArrayContent() : null;
    }

    public function renderAdd(){

    }

    public function renderEdit($id){
        $item = $this->userManager->get($id);
        if (!$item) {
            $this->flashMessage('Nothing found', 'alert-danger');
            $this->redirect('default');
        }
        $this['userForm']->setDefaults($item);
    }

    /**
     * @param $id
     * @throws Nette\Application\AbortException
     */
    public function handleDelete($id)
    {
        $this->userManager->delete($id) ? $this->flashMessage('Deleted', 'alert-success') : $this->flashMessage(
            'Error',
            'alert-danger'
        );
        $this->isAjax() ? $this->redrawControl() : $this->redirect('this');
    }

    protected function createComponentUserForm(): Form
    {
        $this->userForm->id = $this->id;
        return $this->userForm->create();
    }
}
