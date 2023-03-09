<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use App\Form\RecyclageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use App\Entity\Recyclage;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RecyclageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Recyclage::class;
    }
    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('don');

        // yield NumberField::new('quantity');
        // // ...

        // // Add the RecyclageType form type
        // $recyclageField = Field::new('recyclage', 'Recyclage Details')
        //     ->setFormType(RecyclageType::class)
        //     ->setFormTypeOptions([
        //         'label' => false,
        //         'inherit_data' => true,
        //         'compound' => true
        //     ]);

        // yield $recyclageField;
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
