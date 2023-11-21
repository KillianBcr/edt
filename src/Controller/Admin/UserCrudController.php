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
                ->hideOnForm(),
            TextField::new('firstname'),
            TextField::new('lastname'),
            TextField::new('phone')
                ->hideOnIndex(),
            TextField::new('address')
                ->hideOnIndex(),
            TextField::new('city')
                ->hideOnIndex(),
            TextField::new('postalCode')
                ->hideOnIndex(),
            TextField::new('login')
                ->hideOnIndex()
                ->setFormTypeOption('disabled', 'disabled'),
            TextField::new('email')
                ->hideOnIndex()
                ->setFormTypeOption('disabled', 'disabled'),
            ArrayField::new('roles'),
        ];
    }
}
