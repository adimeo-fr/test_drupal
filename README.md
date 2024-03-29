# Test Drupal

## Description de l'existant
Le site est déjà installé (profile standard), la db est à la racine du projet.
Un **type de contenu** `Événement` a été créé et des contenus générés avec Devel. Il y a également une **taxonomie** `Type d'événement` avec des termes.

La version du core est la 10.0.9 et le composer lock a été généré sur PHP 8.1.

Les files sont versionnées sous forme d'une archive compressée. Vous êtes invité à créer un fichier `settings.local.php` pour renseigner vos accès à la DB. Le fichier `settings.php` étant lui versionné.

## Consignes

### 1. Faire un bloc custom (plugin annoté)
* s'affichant sur la page de détail d'un événement ;
* et affichant 3 autres événements du même type (taxonomie) que l'événement courant, ordonnés par date de début (asc), et dont la date de fin n'est pas dépassée ;
* S'il y a moins de 3 événements du même type, compléter avec un ou plusieurs événements d'autres types, ordonnés par date de début (asc), et dont la date de fin n'est pas dépassée.

### 2. Faire une tache cron
qui dépublie, **de la manière la plus optimale,** les événements dont la date de fin est dépassée à l'aide d'un **QueueWorker**.

## Rendu attendu
Merci de nous envoyer soit un package, ou un lien vers un repository avec :

* uniquement le contenu du dossier "modules/custom" avec vos développements
* **et pourquoi pas** des readme, des commentaires etc. :)

Quoi qu'il arrive **MERCI DE NE PAS FORKER** ce repository.

Merci également de préciser **le temps que vous avez passé**, par mail ou dans un readme par exemple.
