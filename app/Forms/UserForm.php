<?php

declare(strict_types=1);

namespace App\Forms;

use App\Forms\BootstrapRenderTrait;
use App\Model\Service\XmlService;
use Nette\Application\UI\Form;
use Nette\Forms\Container;
use Nette\SmartObject;

class UserForm
{

    public int $copies = 1;

    public int $maxCopies = 100;


    use SmartObject;
    use BootstrapRenderTrait;

    /***/
    public function __construct(
        private XmlService $xmlService
    ) {
    }

    public function create(): Form
    {
        $form = new Form();

        $form->addProtection('Form protection');

        $form->addGroup('Fill user data');
        $multiplier = $form->addMultiplier('leftoptions', function (Container $container, Form $form) {
            $container->addText('name', 'Hodnota')
                ->setRequired(false)
                ->setDefaultValue('');
            $container->addText('value', 'Text možnosti')
                ->setRequired(false)
                ->setDefaultValue('');
        }, $this->copies, $this->maxCopies);

        $multiplier->addCreateButton('Pridať')
            ->addClass('btn btn-info btn-sm');
        $multiplier->addRemoveButton('Odstrániť')
            ->addClass('btn btn-danger btn-sm');

        return $this->setBootstrapRender($form);

    }
}