<?php

namespace App\Controller\Admin;

use App\Entity\Recyclage;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RecyclageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Recyclage::class;
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
