<?php

namespace App\Controller\Admin;

use App\Entity\Semester;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SemesterCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Semester::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Semestres')
            ->setEntityLabelInSingular('Semestre');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            TextField::new('name'),
            DateTimeField::new('startDate'),
            DateTimeField::new('endDate'),
            CollectionField::new('periods'),
        ];
    }
}
