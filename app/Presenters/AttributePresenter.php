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
    private int $copies = 1;

    #[Inject]
    public AttributeForm $attributeForm;

    public function startup()
    {
        parent::startup();
        if ($this->getAction() == 'edit') {
            $this->copies = $this->attributeManager->getCount();
        }
    }

    public function renderDefault()
    {
        $this->template->items = $this->attributeManager->getContent();
    }

    public function renderEdit()
    {
        $this['attributeForm']['attributeitems']->setDefaults($this->attributeManager->getArrayContent());
    }

    public function handleDelete($id)
    {
        \Tracy\Debugger::barDump($this->attributeManager->delete($id));
    }

    protected function createComponentAttributeForm(): Form
    {
        $this->attributeForm->copies = $this->copies;
        return $this->attributeForm->create();
    }
}
