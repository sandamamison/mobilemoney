## 1. Créer les modèles

### ClientModel

Fichier :

`app/Models/ClientModel.php`

- [X] Relier le modèle à la table `clients`
- [X] Définir la clé primaire `id`
- [X] Ajouter une fonction pour chercher un client par téléphone
- [X] Ajouter les validations du téléphone
- [X] Vérifier que le statut est `ACTIF` ou `BLOQUE`

### CompteModel

Fichier :

`app/Models/CompteModel.php`

- [X] Relier le modèle à la table `comptes`
- [X] Ajouter une fonction pour chercher le compte d’un client
- [X] Empêcher un solde négatif dans le code
- [X] Vérifier que le compte est actif

### PrefixeModel

Fichier :

`app/Models/PrefixeModel.php`

- [X] Relier le modèle à la table `prefixes_operateur`
- [X] Ajouter une fonction pour vérifier si un préfixe est valide
- [X] Vérifier que le préfixe est actif

### TypeOperationModel

Fichier :

`app/Models/TypeOperationModel.php`

- [X] Relier le modèle à la table `types_operations`
- [X] Ajouter une fonction pour rechercher un type par son code
- [X] Gérer les codes :
  - [X] `DEPOT`
  - [X] `RETRAIT`
  - [X] `TRANSFERT`

### BaremeFraisModel

Fichier :

`app/Models/BaremeFraisModel.php`

- [X] Relier le modèle à la table `baremes_frais`
- [X] Ajouter une fonction pour trouver les frais selon :
  - [X] le type d’opération ;
  - [X] le montant ;
  - [X] la tranche active.

### OperationModel

Fichier :

`app/Models/OperationModel.php`

- [X] Relier le modèle à la table `operations`
- [X] Autoriser les champs nécessaires
- [X] Ajouter une fonction pour générer une référence unique
- [X] Ajouter une fonction pour récupérer l’historique d’un compte

### MouvementCompteModel

Fichier :

`app/Models/MouvementCompteModel.php`

- [X] Relier le modèle à la table `mouvements_comptes`
- [X] Autoriser les champs nécessaires
- [X] Vérifier que le sens vaut :
  - [X] `CREDIT`
  - [X] `DEBIT`
---

# 2. Créer la connexion automatique

## AuthController

Fichier :

`app/Controllers/AuthController.php`

- [X] Créer la méthode `index()` pour afficher le formulaire
- [X] Créer la méthode `connexion()` pour recevoir le téléphone
- [X] Nettoyer le numéro saisi :
  - [X] supprimer les espaces ;
  - [X] supprimer les tirets ;
  - [X] conserver uniquement les chiffres.
- [X] Vérifier que le numéro contient 10 chiffres
- [X] Extraire les trois premiers chiffres
- [X] Vérifier que le préfixe existe dans `prefixes_operateur`
- [X] Vérifier que le préfixe est actif
- [X] Afficher un message si le préfixe est invalide
- [X] Rechercher le numéro dans la table `clients`
- [X] Créer automatiquement le client s’il n’existe pas
- [X] Créer automatiquement son compte avec un solde de `0`
- [X] Vérifier que le client n’est pas bloqué
- [X] Vérifier que le compte n’est pas bloqué
- [X] Enregistrer les informations dans la session :
  - [X] `client_id`
  - [X] `compte_id`
  - [X] `telephone`
  - [X] `connecte`
- [X] Rediriger vers le tableau de bord client
- [X] Créer la méthode `deconnexion()`

---

# 3. Créer la page de connexion

Fichier :

`app/Views/auth/login.php`

- [X] Ajouter le titre « Connexion Mobile Money »
- [X] Ajouter un champ numéro de téléphone
- [X] Ajouter un bouton « Se connecter »
- [X] Afficher les erreurs de validation
- [X] Afficher les messages de succès ou d’erreur
- [X] Utiliser Bootstrap
- [X] Ajouter une indication comme :
  - [X] Exemple : `0331234567`
  - [X] Préfixes acceptés : `033` et `037`

---

# 4. Protéger les pages du client

- [X] Créer un filtre `ClientAuthFilter`
- [X] Vérifier la présence de `connecte` dans la session
- [X] Rediriger vers `/connexion` lorsque le client n’est pas connecté
- [X] Appliquer le filtre aux routes du côté client

Fichier proposé :

`app/Filters/ClientAuthFilter.php`

---

# 5. Afficher le tableau de bord et le solde

## ClientController

Fichier :

`app/Controllers/ClientController.php`

- [X] Créer la méthode `dashboard()`
- [X] Récupérer le compte depuis la session
- [X] Lire le solde actuel
- [X] Récupérer les dernières opérations
- [X] Envoyer les données à la vue

## Vue du tableau de bord

Fichier :

`app/Views/client/dashboard.php`

- [X] Afficher le numéro de téléphone
- [X] Afficher le solde en ariary
- [X] Ajouter un bouton « Dépôt »
- [X] Ajouter un bouton « Retrait »
- [X] Ajouter un bouton « Transfert »
- [X] Ajouter un bouton « Historique »
- [X] Ajouter un bouton « Déconnexion »
- [X] Afficher les cinq dernières opérations

---

# 6. dépôt

## OperationController

Fichier :

`app/Controllers/OperationController.php`

- [X] Créer la méthode `depotForm()`
- [X] Créer la méthode `depot()`
- [X] Récupérer le montant envoyé
- [X] Vérifier que le montant est un entier
- [X] Vérifier que le montant est supérieur à zéro
- [X] Vérifier que le compte est actif
- [X] Récupérer le solde avant le dépôt
- [X] Calculer le nouveau solde
- [X] Démarrer une transaction
- [X] Créer une ligne dans `operations`
- [X] Mettre :
  - [X] `compte_source_id = NULL`
  - [X] `compte_destination_id = compte du client`
  - [X] `frais = 0`
  - [X] `statut = VALIDEE`
- [X] Mettre à jour le solde du compte
- [X] Créer un mouvement `CREDIT`
- [X] Enregistrer le solde avant et après
- [X] Valider la transaction
- [X] Annuler la transaction en cas d’erreur
- [X] Afficher un message de réussite
- [X] Rediriger vers le tableau de bord

## Vue dépôt

Fichier :

`app/Views/client/depot.php`

- [X] Afficher le solde actuel
- [X] Ajouter un champ montant
- [X] Ajouter un bouton « Effectuer le dépôt »
- [X] Ajouter un bouton « Retour »
- [X] Afficher les erreurs

---

# 7. retrait

- [X] Créer la méthode `retraitForm()`
- [X] Créer la méthode `retrait()`
- [X] Vérifier que le montant est supérieur à zéro
- [X] Rechercher le type d’opération `RETRAIT`
- [X] Trouver les frais correspondant à la tranche du montant
- [X] Afficher une erreur si aucune tranche n’existe
- [X] Calculer :

```text
