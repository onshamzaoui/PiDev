<?php

namespace App\Controller\Admin;

use App\Entity\AffectationService;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AffectationServiceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AffectationService::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
