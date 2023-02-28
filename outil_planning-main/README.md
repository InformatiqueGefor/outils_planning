# Outils pour les plannings Gefor

## Installation

Installation de symfony 6 avec composer ce qui permet de mettre à jour les dépendances
php version 8.1

```Shell
composer update
```

installation des dépendances de symfony pour l'éxecution du projet

```Shell
composer require symfony/apache-pack
composer require doctrine/annotations => annotation pour l\'orm doctrine
composer require twig/twig => moteur de template
composer require twig/extra-bundle
composer require twig/string-extra => concatenation dans twig
composer require symfony/asset => utilisation pour les chemins d\'accès avec twig
composer require --dev symfony/maker-bundle => le maker de symfony
composer require --dev symfony/profiler-pack => debuger de symfony
composer require symfony/debug-bundle
composer require symfony/orm-pack -W => usage de doctrine 
```

## Configuration  du fichier .env.local

En production on peut laisser la configuration dans le fichier (.env) . Mais je conseille de créer un fichier (.env.local )pour plus de sécurité. Ce fichier est nécessaire pour la configuration de la base de données et des variables d'environnement.
Pour la base de données voici les configurations possible selon le gestionnaire choisi <https://symfony.com/doc/current/doctrine.html#configuring-the-database> .

## Documentation générale du projet

Voir la section docs pour avoir le MCD du projet

Liens vers le deploiement avec symfony
https://symfony.com/doc/current/deployment.html
