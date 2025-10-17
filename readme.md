
# MuséoTime
MuséoTime est une application web qui transforme la consultation de la base de données des musées de France en une expérience personnelle et organisée. Explorez, sauvegardez, et organisez vos futures visites culturelles en toute simplicité.

![Image de MuséoTime](https://github.com/MeyDetour/museoTime/blob/master/public/Screenshot%20from%202025-10-17%2012-07-48.png)

## Contexte du Projet
Ce projet a été développé en réponse à une initiative du Ministère de la Culture visant à valoriser les données Open Data de la base Muséofile, qui recense les Musées de France.

L'objectif était de créer un site web permettant non seulement de consulter cette riche base de données via son API, mais aussi d'offrir aux utilisateurs des outils pour s'approprier ces informations et planifier leurs découvertes culturelles.

## Fonctionnalités Principales

MuséoTime a été pensé pour être un compagnon de visite pratique et intuitif.

* Recherche et Filtres Intelligents : Trouvez facilement un musée par ville, région, ou thématique pour affiner vos recherches.
* Listes de Favoris Personnalisées : Ne vous contentez pas d'un simple "j'aime". Créez autant de listes que vous le souhaitez pour organiser vos trouvailles : "À voir ce week-end", "Pour les vacances en Bretagne", "Idées pour Maman", etc.
* Espace Utilisateur Minimaliste : Un simple nom d'utilisateur suffit. Pas d'emails, pas de notifications inutiles. Votre compte est un espace personnel pour gérer vos listes et vos envies.
* Partage entre Amis : Vous avez trouvé une pépite ? Partagez-la en un clic avec un autre utilisateur de MuséoTime directement depuis la fiche du musée.
* Fiches Détaillées avec Carte : Accédez à toutes les informations essentielles d'un musée, y compris sa localisation sur une carte interactive.

## Notre Philosophie : L'Utilité Avant Tout

Nous avons fait des choix de conception forts pour garantir une expérience utilisateur respectueuse et ciblée :
* Pas de Données Superflues : Nous ne demandons ni votre nom, ni votre prénom, ni votre adresse email. Un username est suffisant pour les interactions sociales comme le partage.
* Zéro Pollution par Email : L'application n'envoie aucun email (newsletters, notifications). Nous contribuons ainsi à un environnement numérique plus sain et réduisons notre empreinte carbone.
* Centré sur l'Organisation Personnelle : MuséoTime n'est pas un réseau social, mais un outil personnel. Le but est de vous aider à organiser vos propres sorties culturelles, pas de vous connecter au monde entier.


## Post-Mortem & Réflexions
Ce projet a été une aventure intense et enrichissante. Voici un retour d'expérience transparent sur les réussites, les défis rencontrés et les ambitions futures pour MuséoTime.

* Points Forts
    + Un Système de Filtres Puissant : Le point fort le plus visible de l'application est sans doute son système de filtrage. Face à une base de données contenant des milliers d'entrées, il permet de trier efficacement les musées pour trouver la perle rare qui correspond aux goûts de chacun. C'est le cœur de l'expérience de découverte.
    + Un Design Épuré et Intuitif (UX/UI) : L'interface a été conçue pour être à la fois esthétique et fonctionnelle. Le design est minimaliste et le schéma de navigation reste simple et familier. Le but était de créer une expérience utilisateur (UX) fluide où l'on trouve ce que l'on cherche sans effort, soutenue par une interface utilisateur (UI) agréable.
    + Une Philosophie du "Less is more " : J'ai bâti ce projet avec la volonté de supprimer le superflu. C'est un choix délibéré de ne pas demander d'adresse e-mail à l'inscription. Pas de mail de confirmation, pas de newsletter. Cela respecte la vie privée de l'utilisateur et participe à un numérique moins polluant et moins intrusif.

* Défis Rencontrés
  + Le Temps : Le principal défi a été le manque de temps. Le projet a été réalisé en 3 jours de développement pur au lieu des 5 initialement envisagés, le reste du temps étant consacré à la documentation et aux présentations. Cela a nécessité une priorisation stricte des fonctionnalités essentielles.
 + Les Limites de l'API Externe : L'API Muséofile présentait une contrainte majeure : l'absence d'images pour les musées. Pour ne pas présenter un site visuellement pauvre, il a fallu contourner ce problème côté backend en créant une table dédiée pour associer manuellement un ID de musée à une image pertinente.

* Prochaines Étapes et Ambitions Futures
  + Recherche par Géolocalisation "Musées près de chez moi" : L'objectif est d'utiliser la localisation de l'utilisateur (renseignée de manière privée et sécurisée) pour lui proposer les musées les plus proches de lui. Fini les recommandations de musées magnifiques... mais situés à 500 km !
  + Calcul du Temps de Trajet : En complément de la géolocalisation, l'application pourrait estimer le temps de trajet (en voiture, en transports en commun) jusqu'au musée pour aider à planifier concrètement la visite.
  + Partage Externe Simplifié : Même si copier/coller l'URL fonctionne, je souhaite intégrer des options de partage plus directes vers des applications comme WhatsApp ou par SMS, pour ceux qui n'ont pas de compte MuséoTime.
  + "Shazam" des Bâtiments : Reconnaissance par Photo  L'idée la plus ambitieuse : permettre à un utilisateur de prendre en photo un bâtiment culturel pour que l'application l'identifie instantanément et lui affiche la fiche du musée correspondant.
  
Merci d'avoir pris le temps de lire ce retour. Profitez bien de la découverte du projet !


## Installation et Lancement
Pour installer et lancer le projet sur votre machine locale, veuillez suivre notre <a href="https://github.com/MeyDetour/museoTime/blob/master/installation.md">GUIDE D'INSTALLATION DÉTAILLÉ</a>.








