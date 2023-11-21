<?php

namespace App\Controller\Admin;

use App\Entity\Assignment;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class AssignmentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Assignment::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Affectation')
            ->setEntityLabelInPlural('Affectations');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
        ];
    }
}
