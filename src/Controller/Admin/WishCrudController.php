<?php

namespace App\Controller\Admin;

use App\Entity\Wish;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class WishCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Wish::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Voeux')
            ->setEntityLabelInSingular('Voeu');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex()->setLabel('ID'),
            IntegerField::new('chosenGroups')->setLabel('Groupes choisis'),
            AssociationField::new('groupeType')->setLabel('Type de groupe'),
            AssociationField::new('subjectId')->setLabel('Matière'),
            AssociationField::new('wishUser')->setLabel('Utilisateur'),
            BooleanField::new('isAccepted')->setLabel('Status'),
        ];
    }
}

