<?php

namespace App\Controller\Admin;

use App\Entity\Objet;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ObjetCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Objet::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('nom'),
            TextEditorField::new('description'),
            ImageField::new('image')
                ->setRequired(false)
                ->setBasePath('uploads/')
                ->setUploadDir('public/uploads/'),
            AssociationField::new('categorie'),
            AssociationField::new('user'),
        ];
    }
    
}
