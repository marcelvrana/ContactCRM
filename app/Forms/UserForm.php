<?php

declare(strict_types=1);

namespace App\Forms;

use App\Constant\Constant;
use App\Forms\BootstrapRenderTrait;
use App\Model\Manager\AttributeManager;
use App\Model\Manager\UserManager;
use Nette\Application\UI\Form;
use Nette\Forms\Container;
use Nette\SmartObject;
use Nette\Utils\ArrayHash;

class UserForm
{

    public null|int $id;

    use BootstrapRenderTrait;

    /***/
    public function __construct(
        private UserManager $userManager,
        private AttributeManager $attributeManager
    ) {
    }

    public function create(): Form
    {
        $form = new Form();

        $form->addProtection('Form protection');


        $form->addGroup('Fill user data');
        foreach($this->attributeManager->getArrayContent() as $item){

            if(is_string($item['param'])){
                switch ($item['param']){
                    case 'date':
                        $form->addText($item['webalized'], $item['name'])
                        ->setHtmlAttribute('data-param', $item['param'] );
                        break;
                    case 'sex':
                        $form->addSelect($item['webalized'], $item['name'], Constant::SEX_ITEMS);
                        break;
                    default:
                        break;

                }
            } else {
                $form->addText($item['webalized'], $item['name']);
            }

        }
        $form->addHidden('id')
            ->setRequired(false)
            ->setDefaultValue('');

        $form->addGroup('Save');
        $form->addSubmit('submit', 'Save')
            ->setHtmlAttribute('class', 'ajax');

        $form->onError[] = [$this, 'errorForm'];
        $form->onSuccess[] = [$this, 'successForm'];

        return $this->setBootstrapRender($form);

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
            $this->userManager->add($values);
            $form->getPresenter()->flashMessage('Saved', 'alert-success');
        } catch (\Exception $e) {
            \Tracy\Debugger::log($e->getMessage());
            $form->getPresenter()->flashMessage('Error: '. $e->getMessage(), 'alert-danger');
        }
        $form->getPresenter()->redrawControl();
    }

}