<?php

declare(strict_types=1);

namespace App\Presenters;


use App\Forms\AttributeForm;
use App\Presenters\BasePresenter;
use Nette;
use Nette\Application\UI\Form;
use Nette\DI\Attributes\Inject;


final class AttributePresenter extends BasePresenter
{

    #[Inject]
    public AttributeForm $attributeForm;


    public function renderDefault(){

    }

    public function renderEdit(){

    }

    public function handleDelete($id){

    }

    protected function createComponentAttributeForm(): Form
    {
        return $this->attributeForm->create();
    }
}
