# PRAL Calc
Cette API REST gère le suivi nutritionnel individuel, le calcul de l'indice PRAL (Potential Renal Acid Load), 
la création de recettes personnalisées, la gestion d'un journal de bord alimentaire ainsi que l'authentification 
et l'autorisation des utilisateurs.

## Architecture
Cette application Symfony fonctionne comme une API backend. Elle expose les données nutritionnelles de la table Ciqual 
consommées par une application cliente développée en React.
Le dépôt du frontend React est accessible [ici](https://github.com/divineion/client-pral-calc)

## 1. Rôle
L'API fournit les fonctionnalités suivantes :
 - Inscription avec vérification de l'adresse e-mail
 - Authentification par JWT
 - Consultation d'une table d'aliments avec macro- et micronutriments, et calcul du potentiel alcalinisant ou acidifiant
 - Gestion de recette (création et catégorisation)
 - Journal alimentaire 
 - Configuraton des préférences d'affichage des nutriments

## 2. Choix techniques
 - Langage : **PHP 8.2+**
 - Framework : **Symfony 7.1**
 - Base de donnée : **MySQL +**

## 3. Configuration locale
L'application utilise le composant Secrets pour stocker sa configuration, complété par le fichier `.env` et ses surcharges locales.   

### Paramètres généraux (.env)
 - DATABASE_URL | URL de connexion à la base de données  |  mysql://%env(resolve:DATABASE_USER)%:%env(resolve:DATABASE_PASSWORD)%@%env(resolve:DATABASE_HOST)%/%env(resolve:DATABASE_NAME)%?%env(resolve:DATABASE_SERVER_VERSION)%
 - JWT_SECRET_KEY | Emplacement de la clé privée RSA pour signer les JWT  | `%kernel.project_dir%/config/jwt/private.pem`
 - JWT_PUBLIC_KEY | Emplacement de la clé publique RSA pour vérifier les JWT  | `%kernel.project_dir%/config/jwt/public.pem`
 - FRONTEND_URL | URL du client frontend  | `http://localhost:3000`
 - CORS_ALLOW_ORIGIN | Domaines autorisés pour les requêtes cross-origin | `^https?://(localhost|127\.0\.0\.1)(:[0-9]+)?$`
 - JWT_PASSPHRASE | phrase de protection de la clé privée | Renseignée à la génération (peut être vide)

### Secrets chiffrés (Symfony Secrets)
 - DATABASE_HOST | nom d'hôte du serveur de base de données | exemple : `127.0.0.1`
 - DATABASE_USER | identifiant de connexion à la base de données | à renseigner
 - DATABASE_PASSWORD | mot de passe de l'utilisateur de la base de données | à renseigner
 - DATABASE_NAME | nom de la base de données | `pral_dev`
 - DATABASE_SERVER_VERSION | version du moteur SQL et encodage | exemple : `serverVersion=10.11.14-MariaDB&charset=utf8mb4`
 - MAILER_DSN | DSN complet du service d'envoi d'e-mails | à renseigner (peut être `null://null` en dev)
 - MAILER_DSN_PASSWORD | mot de passe de connexion au service d'envoi d'email | à renseigner (peut être `null` en dev)
 - MAILER_DSN_SERVER | serveur d'envoi d'email | à renseigner (peut être `null` en dev)
 - MAILER_DSN_USERNAME | nom d"utilisateur du serveur d'envoi d'email | à renseigner (peut être `null` en dev)

## 4. Principaux endpoints
### Authentification
 - POST /api/register : inscription d'un utilisateur
 - GET /api/verify-email/{token} : activation du compte via le jeton de validation
 - POST /api/login_check : authentification (email/mot de passe), émission du token JWT et injection du cookie REFRESH_TOKEN
 - POST /api/token/refresh : renouvellement automatique du jeton d'accès via le cookie de rafraîchissement 
 - POST /api/logout : révocation du refresh token en base et purge du cookie REFRESH_TOKEN

### Profil utilisateur
 - GET /api/profile/{id} : récupération des préférences d'affichage des nutriments
 - POST /api/profile/{id} : mise à jour des préférences d'affichage
 - GET /api/profile/verify/{username} : vérification de la disponibilité d'un nom d'utilisateur (route publique)
 - DELETE /api/profile/{id}/delete : suppression d'un compte utilisateur

### Aliments / données nutritionnelles
 - GET /api/aliments : liste complète des aliments référencés (libellé, identifiant, indice PRAL)
 - GET /api/aliment/{id} : fiche détaillée des valeurs nutritionnelles complètes d'un aliment

### Recettes
 - GET /api/recipes : consultation de la liste des recettes disponibles
 - GET /api/recipes/latest : récupération des 3 dernières recettes publiées
 - GET /api/recipe/{id} : fiche d'une recette données (instructions, aliments associés, catégories, indice PRAL)
 - GET /api/recipes/user/{id} : liste des recettes créées par un utilisateur spécifique
 - POST /api/recipe : création d'une nouvelle recette

### Journal alimentaires (événements)
 - POST /api/event : enregistrement d'une entrée dans le journal alimentaire
 - GET /api/event/{id} : consultation du détail d'un événement
 - POST /api/event/{id} : mise à jour d'un événement existant 
 - DELETE /api/event/{id} : suppression d'un événement du journal 
 - GET /api/profile/{id}/events : récupération des événements d'un utilisateur sur une période donnée

### Taxonomie
 - GET /api/categories : liste des catégories principales de recettes
 - GET /api/category/{id} : détail d'une catégorie
 - GET /api/subcategories : liste de l'ensemble des sous-catégories
 - GET /api/category/{id}/subcategories : liste des sous-catégories rattachées à une catégorie donnée.

## 5. Démarrage rapide
### Prérequis
 - PHP 8.2+ (CLI)
 - Composer 2.x
 - MySQL 8+ démarré
 - OpenSSL

### 1. Cloner et installer les dépendances
```
git clone https://github.com/divineion/api-pral-calc
cd pral-calc-api
composer install`
```
### 2. Configuration des secrets d'environnement
Deux approches sont possibles pour configurer les identifiants locaux :

#### Soit la clé de déchiffrement Symfony Secrets :
Déposez le fichier de clé privée dev.decrypt.private.php dans config/secrets/dev/. Les secrets du projet seront déchiffrés automatiquement.

#### Soit par surcharge manuelle dans .env.local :
Créez un fichier .env.local à la racine pour surcharger directement la variable de connexion :
DATABASE_URL="mysql://root:root@127.0.0.1:3306/pral_dev?serverVersion=10.11.14-MariaDB&charset=utf8mb4"
MAILER_DSN="null://null"

#### 3. Générer la paire de clés cryptographiques JWT
php bin/console lexik:jwt:generate-keypair

### 4. Initialiser la base de données et les tables
#### Créer la base de données
php bin/console doctrine:database:create

#### Exécuter les migrations du schéma
`php bin/console doctrine:migrations:migrate --no-interaction
`
#### Charger du référentiel (catégories, sous-catégories, constantes)
`php bin/console doctrine:fixtures:load --no-interaction
`
### 5. Importer la table nutritionnelle CALNUT 2020
`php bin/console app:import-calnut`
### 6.Lancer le serveur local
`symfony server:start`

## 6. Roadmap de refonte
La version actuelle correspond à la première itération fonctionnelle de l'application. 
Un plan de refonte est défini afin d'aligner le socle sur les standards de développement et les principes de Clean Code.

### Sécurité
 - élimination des failles IDOR : suppression de l'envoi d'identifiants utilisateur (user_id) dans les requêtes de mutation
(POST /api/recipe, POST /api/event) : l'utilisateur sera systématiquement déduit du jeton de sécurité.
 - utilisation de Symfony Voters pour le contrôle d'accès sur les ressources au lieu de contrôles manuels dans les contrôleurs.
 - remplacement de la route /api/user/{email} par un endpoint `/api/me`.

### DTO
 - données d'entrée : généralisation de #[MapRequestPayload] sur l'ensemble des endpoints (CreateRecipeRequest, CreateEventRequest, etc.), 
en remplacement des json_decode($request->getContent()) manuels.
 - données en sortie : encapsulation des données sortantes via des objets typés. 

### Modèle de données
 - migration des colonnes temporelles en chaînes de caractères vers des types Doctrine natifs (DATETIME_IMMUTABLE, DATE_IMMUTABLE)
pour garantir systématiquement la validité des dates.
 - abandon du type LONGBLOB pour les visuels de recettes au profit d'un stockage des fichiers sur serveur avec persistance du chemin relatif en base. 
 - découplage des préférences par défaut de l'utilisateur, actuellement rattachées à des constantes de AppFixtures, pour éviter que l'application ne dépende de fichiers de dev/test pour fonctionner.

### Industrialisation
 - suppression de l'attribut temporaire #[AllowDynamicProperties] et injection propre des dépendances 
via constructeurs readonly.
 - tests fonctionnels pour sécuriser l'authentification et l'ensemble des routes.