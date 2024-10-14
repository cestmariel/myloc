<?php

namespace App\Controller\Admin;

use App\Entity\Emprunt;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EmpruntCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Emprunt::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('user', User::class),
            AssociationField::new('objet'),
            DateTimeField::new('debut'),
            DateTimeField::new('fin'),
        ];
    }
    
}
