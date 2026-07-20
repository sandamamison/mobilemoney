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

# V2

## 1. GÃ©rer les autres opÃ©rateurs

Fichiers principaux :

`app/Models/AutreOperateurModel.php`

`app/Controllers/AutreOperateurController.php`

- [x] Ajouter les autres opÃ©rateurs
- [x] Modifier leur nom et leur statut
- [x] DÃ©finir le pourcentage de commission
- [x] Activer ou dÃ©sactiver un opÃ©rateur

---

## 2. Configurer les prÃ©fixes externes

Fichiers principaux :

`app/Models/PrefixeAutreOperateurModel.php`

`app/Views/operateur/autres_operateurs.php`

- [ ] Ajouter les prÃ©fixes comme `032` et `031`
- [ ] Associer chaque prÃ©fixe Ã  un opÃ©rateur
- [ ] EmpÃªcher les doublons
- [ ] Activer ou dÃ©sactiver un prÃ©fixe

---

## 3. SÃ©parer les gains

Fichiers principaux :

`app/Controllers/GainController.php`

`app/Views/operateur/gains.php`

- [ ] Afficher les gains de notre opÃ©rateur
- [ ] Afficher sÃ©parÃ©ment les gains des transferts externes
- [ ] Afficher les frais de retrait
- [ ] Afficher les commissions reÃ§ues par opÃ©rateur

---

## 4. Afficher les montants Ã  envoyer

Fichiers principaux :

`app/Controllers/ReglementOperateurController.php`

`app/Views/operateur/montants_a_envoyer.php`

- [ ] Regrouper les transferts par opÃ©rateur
- [ ] Additionner les montants envoyÃ©s
- [ ] Ajouter les frais de retrait inclus
- [ ] Afficher le total Ã  envoyer Ã  chaque opÃ©rateur

---

## 5. Mettre Ã  jour le tableau de bord opÃ©rateur

Fichier principal :

`app/Views/operateur/dashboard.php`

- [ ] Ajouter le nombre dâ€™opÃ©rateurs externes
- [ ] Ajouter les gains internes
- [ ] Ajouter les gains externes
- [ ] Ajouter les montants Ã  rÃ©gler
- [ ] Ajouter les liens vers les nouvelles pages

---

## 6. Tester et publier

- [ ] Tester lâ€™ajout dâ€™un opÃ©rateur
- [ ] Tester lâ€™ajout dâ€™un prÃ©fixe
- [ ] Tester la modification dâ€™une commission
- [ ] VÃ©rifier la sÃ©paration des gains
- [ ] VÃ©rifier les montants Ã  envoyer
- [ ] Mettre Ã  jour `Taches.md`
- [ ] Faire un commit aprÃ¨s chaque grande Ã©tape
- [ ] Publier la branche `feature/v2-operateur`

---

## Ordre conseillÃ©

1. Gestion des opÃ©rateurs
2. Configuration des prÃ©fixes
3. Calcul et sÃ©paration des gains
4. Montants Ã  envoyer
5. Tableau de bord
6. Tests et publication
