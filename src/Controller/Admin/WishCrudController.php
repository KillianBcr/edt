<?php

namespace App\Controller\Admin;

use App\Entity\Wish;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

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
            IdField::new('id')->onlyOnIndex()->setLabel('ID')->hideOnForm(),

            IntegerField::new('chosenGroups')->setLabel('Nombre de groupes choisis'),

            TextField::new('groupeType', 'Type de groupe')
                ->formatValue(fn ($value, $entity) => $entity->getGroupeType()->getType()),

            AssociationField::new('subjectId')
                ->setLabel('Matière')
                ->formatValue(fn ($value, $entity) => $entity->getSubjectId()->getName()),

            AssociationField::new('wishUser')
                ->setLabel('Utilisateur')
                ->formatValue(function ($value, $entity) {
                    return $entity->getWishUser()->getFirstname().' '.$entity->getWishUser()->getLastname();
                }),

            BooleanField::new('isAccepted')->setLabel('Status'),
        ];
    }
}
