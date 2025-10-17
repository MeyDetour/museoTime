#MuséoTime - Guide d'Installation
Bienvenue sur MuséoTime ! Ce guide vous aidera à installer et à lancer le projet sur votre machine locale. L'application est développée avec le framework Symfony et utilise PostgreSQL comme base de données.

# Prérequis
Avant de commencer, assurez-vous d'avoir installé les outils suivants sur votre système.
* PHP (version 8.1 ou supérieure)
    + Windows : Utilisez XAMPP ou WampServer.
    + macOS : Utilisez Homebrew (brew install php).
    + Linux (Debian/Ubuntu) : `sudo apt update && sudo apt install php php-cli php-pgsql php-mbstring php-xml php-gd`.
<a href="https://dyma.fr/blog/installation-de-php/?campaignId=22795711356&device=c&utm_source=google&gad_source=1&gad_campaignid=22805258542&gbraid=0AAAAADPXRQlgn_hiTgyU2_QCVE5qWXTYx&gclid=CjwKCAjwr8LHBhBKEiwAy47uUq2b223cEziSZHvDAO5Ir4t8hm35B_3803rDbzMIVjd9k8fbJSgLKhoCf3YQAvD_BwE">🔗 Guide d'installation de PHP</a>

* Composer
  + C'est le gestionnaire de dépendances pour PHP
  + <a href="https://getcomposer.org/download/">🔗 Instructions d'installation de Composer</a>


* Symfony CLI
  + L'outil en ligne de commande pour faciliter le développement avec Symfony.
 + <a href="https://symfony.com/download">Télécharger la CLI Symfony</a>

* PostgreSQL
  + Notre système de gestion de base de données.
 + <a href="https://www.postgresql.org/download/">🔗 Télécharger PostgreSQL</a>
 + Important : Après l'installation, vous devrez créer un utilisateur et une base de données dédiés à ce projet.
   ```SQL
CREATE USER myuser WITH PASSWORD 'mypassword';
CREATE DATABASE mydatabase OWNER myuser;
   ```




