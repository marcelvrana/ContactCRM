<?php

declare(strict_types=1);

namespace App\Forms;

use App\Forms\BootstrapRenderTrait;
use App\Constant\Constant;
use App\Model\Manager\AttributeManager;
use Nette\Application\UI\Form;
use Nette\Forms\Container;
use Nette\SmartObject;
use Nette\Utils\ArrayHash;
use Nette\Utils\Arrays;
use Nette\Utils\Strings;

class AttributeForm
{

    public int $copies = 1;

    public int $maxCopies = 100;


    use SmartObject;
    use BootstrapRenderTrait;

    /***/
    public function __construct(
        private AttributeManager $attributeManager
    ) {
    }

    public function create(): Form
    {
        $form = new Form();

        $form->addProtection('Form protection');

        $form->addGroup('Fill attribute data');
        $multiplier = $form->addMultiplier('attributeitems', function (Container $container, Form $form) {
            $container->addText('name', 'Attribute name')
                ->setRequired(false)
                ->setDefaultValue('');

            $container->addSelect('param', 'Parameter (if needed)', Constant::ATTR_PARAMS)
                ->setRequired(false)
                ->setDefaultValue('');

            $container->addHidden('id')
                ->setRequired(false)
                ->setDefaultValue('');

        }, $this->copies, $this->maxCopies);

        $multiplier->addCreateButton('Add Item')
            ->addClass('btn btn-info btn-sm');

        $multiplier->addRemoveButton('Delete Item')
            ->addClass('btn btn-danger btn-sm');


        $form->addGroup('Save');
        $form->addSubmit('submit', 'Save')
        ->setHtmlAttribute('class', 'ajax');

        $form->onValidate[] = [$this, 'validateForm'];
        $form->onError[] = [$this, 'errorForm'];
        $form->onSuccess[] = [$this, 'successForm'];

        return $this->setBootstrapRender($form);

    }

    /**
     * @param Form $form
     * @param ArrayHash $values
     */
    public function validateForm(Form $form, ArrayHash $values)
    {
        if ($form->isSubmitted()->getName() == 'multiplier_remover' || $form->isSubmitted()->getName(
            ) == 'multiplier_creator') {
            $form->getPresenter()->redrawControl();
        }
    }


    /**
     * @param Form $form
     */
    public function errorForm(Form $form): void
    {
        if ($form->getPresenter()->isAjax()) {
            $form->getPresenter()->redrawControl();
        }
    }

    /**
     * @param $form
     * @param $values
     */
    public function successForm($form, $values): void
    {
        try {
            $this->attributeManager->update($values->attributeitems);
            $form->getPresenter()->flashMessage('Saved', 'alert-success');
        } catch (\Exception $e) {
            \Tracy\Debugger::log($e->getMessage());
            $form->getPresenter()->flashMessage('Error: '. $e->getMessage(), 'alert-danger');
        }
        $form->getPresenter()->redrawControl();
    }

}