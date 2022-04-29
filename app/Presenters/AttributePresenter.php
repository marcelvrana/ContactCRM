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

    /**
     *
     */
    public function startup()
    {
        parent::startup();
        if ($this->getAction() == 'edit') {
            if ($this->attributeManager->exist()) {
                $this->copies = $this->attributeManager->getCount();
            }
        }
    }

    /**
     *
     */
    public function renderDefault()
    {
        $this->template->items = $this->attributeManager->exist() ? $this->attributeManager->getContent() : null;
    }


    /**
     *
     */
    public function renderEdit()
    {
        if ($this->attributeManager->exist()) {
            $this['attributeForm']['attributeitems']->setDefaults($this->attributeManager->getArrayContent());
        }
    }

    /**
     * @param $id
     * @throws Nette\Application\AbortException
     */
    public function handleDelete($id)
    {
        $this->attributeManager->delete($id) ? $this->flashMessage('Deleted', 'alert-success') : $this->flashMessage(
            'Error',
            'alert-danger'
        );
        $this->isAjax() ? $this->redrawControl() : $this->redirect('this');
    }

    /**
     * @return Form
     */
    protected function createComponentAttributeForm(): Form
    {
        $this->attributeForm->copies = $this->copies;
        return $this->attributeForm->create();
    }
}
