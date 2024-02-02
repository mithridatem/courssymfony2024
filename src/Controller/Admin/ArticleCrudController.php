<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
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
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Liste des Articles')
            ->setPageTitle('edit', 'Modifier l\'article')
            ->setPageTitle('new', 'Ajouter un article')
        ;
    }
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            //Nom des boutons page liste
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setIcon('fa-solid fa-plus')->setLabel('Ajouter');
            })
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                return $action->setLabel('Modifier');
            })
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action->setLabel('Supprimer');
            })
            //nom des boutons page modifier
            ->update(Crud::PAGE_EDIT, Action::SAVE_AND_RETURN, function (Action $action) {
                return $action->setLabel('Sauvegarder');
            })
            ->remove(Crud::PAGE_EDIT,Action::SAVE_AND_CONTINUE)
            //nom des boutons page ajout
            ->update(Crud::PAGE_NEW, Action::SAVE_AND_RETURN, function (Action $action) {
                return $action->setLabel('Modifier');
            })
            ->remove(Crud::PAGE_NEW,Action::SAVE_AND_ADD_ANOTHER)
        ;
    }
}
