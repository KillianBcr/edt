<?php

namespace App\Controller\Admin;

use App\Entity\Subject;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SubjectCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Subject::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Matière')
            ->setEntityLabelInPlural('Matières');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),

            TextField::new('name'),

            DateTimeField::new('firstWeek')
                ->hideOnIndex(),

            DateTimeField::new('lastWeek')
                ->hideOnIndex(),

            AssociationField::new('subjectCode')
                ->autocomplete()
                ->setRequired(true)
                ->setLabel('Code de la matière')
                ->formatValue(fn ($value, $entity) => $entity->getSubjectCode()->getCode())
                ->onlyOnIndex(),

            IntegerField::new('hoursTotal'),
        ];
    }
}
