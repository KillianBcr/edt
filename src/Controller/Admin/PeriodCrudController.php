<?php

namespace App\Controller\Admin;

use App\Entity\Period;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PeriodCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Period::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Periodes')
            ->setEntityLabelInSingular('Periode');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm()
                ->setLabel('Identifiant'),
            TextField::new('name')
                ->setLabel('Nom'),
            TextField::new('description')
                ->setLabel('Description')
                ->hideOnIndex(),
            DateTimeField::new('weekStart')
                ->setLabel('Début de la semaine'),
            DateTimeField::new('weekEnd')
                ->setLabel('Fin de la semaine'),
            ArrayField::new('semester')
                ->setLabel('Semestre')
                ->hideOnIndex(),
        ];
    }
}
