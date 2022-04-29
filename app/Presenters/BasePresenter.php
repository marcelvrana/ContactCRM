<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Model\Manager\AttributeManager;
use App\Model\Manager\UserManager;
use Nette;
use Nette\DI\Attributes\Inject;


class BasePresenter extends Nette\Application\UI\Presenter
{
    #[Inject]
    public AttributeManager $attributeManager;

    #[Inject]
    public UserManager $userManager;

}
