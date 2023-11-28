<?php

namespace App\Controller\Admin;

use App\Entity\Year;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class YearCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Year::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Année')
            ->setEntityLabelInPlural('Années');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            IntegerField::new('startYear'),
            IntegerField::new('endYear'),
            TextField::new('academicYear'),
            AssociationField::new('subjects'),
        ];
    }
}

