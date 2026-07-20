

# v1

## Miaraka

- Configuration de CodeIgniter 4
- Création du layout Bootstrap
- Définition des routes
- Tests des opérations
- Correction des erreurs
- Création du tag v1


## Sanda — Côté opérateur

- Création de la base SQLite
- Création du fichier base.sql
- Gestion des préfixes
- Gestion des types d'opérations
- Gestion des barèmes
- Affichage des comptes clients
- Calcul des gains opérateur

## Roberto — Côté client

- Connexion automatique avec le numéro
- Création automatique du client et du compte
- Affichage du solde
- Dépôt
- Retrait
- Transfert
- Historique des opérations


# v2

## Sanda — Côté opérateur

- Création de la table des autres opérateurs
- Gestion des préfixes externes
- Configuration des commissions en pourcentage
- Création des pages de configuration
- Séparation des gains internes et externes
- Situation des montants à envoyer aux opérateurs

## Roberto — Côté client

- Détection automatique de l’opérateur du destinataire
- Transfert vers les autres opérateurs
- Option d’inclusion des frais de retrait
- Calcul du montant total à débiter
- Envoi multiple
- Division du montant entre les numéros
- Mise à jour de l’historique

## Miaraka

- Modification du fichier base.sql
- Tests des calculs
- Tests des transactions
- Fusion des branches
- Correction des erreurs
- Création du tag v2