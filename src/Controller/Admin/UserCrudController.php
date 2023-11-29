<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->setEntityLabelInPlural('Utilisateurs')
        ->setEntityLabelInSingular('Utilisateur');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm()
                ->setLabel('Identifiant'),
            TextField::new('firstname')
                ->setLabel('Prénom'),
            TextField::new('lastname')
                ->setLabel('Nom de famille'),
            TextField::new('phone')
                ->setLabel('Téléphone')
                ->hideOnIndex(),
            TextField::new('address')
                ->setLabel('Adresse')
                ->hideOnIndex(),
            TextField::new('city')
                ->setLabel('Ville')
                ->hideOnIndex(),
            TextField::new('postalCode')
                ->setLabel('Code postal')
                ->hideOnIndex(),
            TextField::new('login')
                ->setLabel('Nom d\'utilisateur')
                ->hideOnIndex()
                ->setFormTypeOption('disabled', 'disabled'),
            TextField::new('email')
                ->setLabel('Adresse e-mail')
                ->hideOnIndex()
                ->setFormTypeOption('disabled', 'disabled'),
            ArrayField::new('roles')
                ->setLabel('Rôles'),
        ];
    }
}
