<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }
/*     public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->setPageTitle('index',  'Liste des articles')
        ->setEntityLabelInSingular('Article')
        ->setEntityLabelInPlural('Articles')
        ;
    }
    */
    public function configureFields(string $pageName): iterable
    {
        return [
            yield IdField::new('id','Identifiant')->hideOnForm(),
            yield TextField::new('title', 'Titre'),
            yield TextEditorField::new('content', 'Contenu'),
            yield DateTimeField::new('creationDate','Date de création')->setFormat('dd/mm/yyyy'),
            yield AssociationField::new('user','Utilisateur'),
            yield AssociationField::new('categories','Catégories'),
        ];
    }
    
}
