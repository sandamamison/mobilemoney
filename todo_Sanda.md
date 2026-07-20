## Base de données
- [x] Création de la base SQLite
- [x] Création du fichier base.sql
## 1. Dashboard opérateur
- [x] Créer la route `$routes->get('/operateur', 'OperateurController::index');`
- [x] Créer la fonction `index()` dans `OperateurController`
  - [x] Récupérer le nombre de clients
  - [x] Récupérer le nombre de comptes
  - [x] Calculer le total des soldes
  - [x] Compter le nombre d’opérations
  - [x] Calculer le total des frais gagnés
- [x] Créer la vue `app/Views/operateur/dashboard.php`
- [x] Afficher les données sur le tableau de bord
## 2. Gestion des préfixes
- [x] Créer le `PrefixeController`
- [x] Implémenter `index()` : Afficher la liste des préfixes
- [x] Implémenter `create()` : Afficher le formulaire d'ajout
- [x] Implémenter `store()` : Enregistrer un préfixe
- [x] Implémenter `delete($id)` : Supprimer un préfixe
- [x] Créer les vues correspondantes (liste, ajout)
## 3. Gestion des types d’opérations
- [x] Créer le controller pour les types d'opérations
- [x] Implémenter `index()` : Afficher la liste
- [x] Implémenter `create()` : Afficher le formulaire d'ajout
- [x] Implémenter `store()` : Enregistrer un type d'opération
- [x] Implémenter `update($id)` : Modifier un type
- [x] Implémenter `delete($id)` : Supprimer un type
- [x] Gérer les champs (libellé, actif/inactif, avec frais ou non)
## 4. Gestion des barèmes de frais
- [x] Créer le `BaremeController`
- [x] Implémenter `index()` : Afficher tous les barèmes
- [x] Implémenter `create()` : Formulaire d’ajout
- [x] Implémenter `store()` : Enregistrer un barème
- [x] Implémenter `edit($id)` : Formulaire de modification
- [x] Implémenter `update($id)` : Modifier un barème
- [x] Implémenter `delete($id)` : Supprimer un barème
- [x] Créer la fonction `getFrais($typeCode, $montant)` dans le Model
## 5. Affichage des comptes clients
- [x] Créer le `CompteController`
- [x] Implémenter `index()` : Liste (Téléphone, Solde, Statut)
- [x] Implémenter `bloquer($id)` : Bloquer un compte
- [x] Implémenter `debloquer($id)` : Débloquer un compte
- [x] Implémenter `show($id)` : Détail du compte (infos, solde, historique)
## 6. Calcul des gains opérateur
- [x] Créer le `GainController`
- [x] Implémenter `index()` : Calculer le total des frais par type (Retraits, Transferts) et le Gain global
## 7. Historique global
- [x] Implémenter `historique()` (dans OperateurController ou autre)
- [x] Afficher Date, Type, Montant, Frais
- [x] Ajouter les filtres (date, type, numéro)