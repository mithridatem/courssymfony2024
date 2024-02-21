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
```env
###> symfony/framework-bundle ###
APP_ENV=dev
APP_SECRET=39c47ce83537bd150f784b44713e2713
###< symfony/framework-bundle ###

###> doctrine/doctrine-bundle ###
# DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/app?serverVersion=8.0.32&charset=utf8mb4"
DATABASE_URL="mysql://!login_bdd!:!mot_de_passe!@127.0.0.1:3306/!nom_bdd!?serverVersion=10.4.24-MariaDB&charset=utf8mb4" 
###< doctrine/doctrine-bundle ###

###> symfony/messenger ###
MESSENGER_TRANSPORT_DSN=doctrine://default?auto_setup=0
###< symfony/messenger ###
###> nelmio/cors-bundle ###
CORS_ALLOW_ORIGIN='^https?://([a-Z]+\.[a-Z]+|localhost|127\.0\.0\.1)(:[0-9]+)?$'
###< nelmio/cors-bundle ###

###< Mail/config ###
SMTP_PASSWORD='##########'
SMTP_ACCOUNT='############'
SMTP_SERVER='###########'
SMTP_PORT=465
###< Mail/config ###

###< Regex ###
REGEX_PASSWORD="/(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{12,}/"
REGEX_MAIL='/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))/i'
###< Regex ###

###> karser/karser-recaptcha3-bundle ###
# Get your API key and secret from https://g.co/recaptcha/v3
RECAPTCHA3_KEY='###########################'
RECAPTCHA3_SECRET='########################'
###< karser/karser-recaptcha3-bundle ###
```
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