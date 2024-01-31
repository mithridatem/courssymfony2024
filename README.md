# courssymfony2024
**1 cloner le repo** :
```git
git clone https://github.com/mithridatem/courssymfony2024
```
**2 se déplacer dans le repo** :
```bash
cd nom_projet
```
**3 créer un fichier .env**,
**4 installer les dépendances** :
```bash
composer install
```
**5 créer la base de données** :
```bash
symfony console doctrine:database:create
```
**6 éffectuer une migration** :
```bash
symfony console doctrine:migrations:migrate
```
**7 lancer le serveur** :
```bash
symfony server:start -d
```
**8 ajouter les données** :
```bash
symfony console doctrine:fixtures:load
```