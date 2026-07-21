-- ============================================================
-- clean_data.sql — Jeu de données complet pour Mobile Money
-- ============================================================
-- Ce fichier réinitialise complètement la base avec des données
-- réalistes et volumineuses pour bien tester le dashboard,
-- les gains et les montants à envoyer.
-- ============================================================

-- 1. Nettoyage des tables (ordre important pour les clés étrangères)
DELETE FROM mouvements_comptes;
DELETE FROM operations;
DELETE FROM comptes;
DELETE FROM clients;
DELETE FROM baremes_frais;
DELETE FROM types_operations;
DELETE FROM prefixes_autres_operateurs;
DELETE FROM autres_operateurs;
DELETE FROM prefixes_operateur;

-- ============================================================
-- 2. Configuration de base (Opérateur principal)
-- ============================================================
INSERT INTO prefixes_operateur (prefixe) VALUES ('034'), ('038');

INSERT INTO types_operations (id, code, libelle, avec_frais) VALUES
    (1, 'DEPOT', 'Dépôt', 0),
    (2, 'RETRAIT', 'Retrait', 1),
    (3, 'TRANSFERT', 'Transfert', 1);

-- Barèmes de frais
INSERT INTO baremes_frais (type_operation_id, montant_min, montant_max, frais) VALUES
    (2, 0, 10000, 100),
    (2, 10001, 50000, 500),
    (2, 50001, 100000, 1000),
    (2, 100001, 1000000, 2000),
    
    (3, 0, 10000, 50),
    (3, 10001, 50000, 200),
    (3, 50001, 100000, 500),
    (3, 100001, 1000000, 1000);

-- ============================================================
-- 3. Partenaires (Autres Opérateurs)
-- ============================================================
INSERT INTO autres_operateurs (id, nom, commission, actif) VALUES
    (1, 'Airtel Money', 1.50, 1),
    (2, 'Orange Money', 2.00, 1),
    (3, 'Mvola', 1.00, 1);

INSERT INTO prefixes_autres_operateurs (prefixe, autre_operateur_id, actif) VALUES
    ('033', 1, 1), -- Airtel
    ('032', 2, 1), -- Orange
    ('037', 2, 1), -- Orange (autre préfixe)
    ('039', 3, 1); -- Mvola

-- ============================================================
-- 4. Clients et Comptes
-- ============================================================
-- Clients Internes (034, 038)
INSERT INTO clients (id, telephone, nom) VALUES
    (1, '0341122233', 'Jean Dupont (Interne)'),
    (2, '0384455566', 'Marie Curie (Interne)'),
    (3, '0347788899', 'Alan Turing (Interne)');

-- Clients Externes
INSERT INTO clients (id, telephone, nom) VALUES
    (4, '0331234567', 'Client Airtel (033)'),
    (5, '0327654321', 'Client Orange 1 (032)'),
    (6, '0371112222', 'Client Orange 2 (037)'),
    (7, '0398889999', 'Client Mvola (039)');

-- Création des comptes avec des soldes initiaux
INSERT INTO comptes (id, client_id, solde, statut) VALUES
    (1, 1, 1500000, 'ACTIF'),
    (2, 2, 500000, 'ACTIF'),
    (3, 3, 25000, 'ACTIF'),
    (4, 4, 0, 'ACTIF'),
    (5, 5, 0, 'ACTIF'),
    (6, 6, 0, 'ACTIF'),
    (7, 7, 0, 'ACTIF');

-- ============================================================
-- 5. Opérations (Retraits, Transferts Internes et Externes)
-- ============================================================

-- RETRAITS (génère des frais de retrait purs)
INSERT INTO operations (id, reference, type_operation_id, compte_source_id, compte_destination_id, montant, frais, statut) VALUES
    (1, 'OP-RET-001', 2, 1, NULL, 50000, 500, 'VALIDEE'), -- Jean Dupont retire 50k
    (2, 'OP-RET-002', 2, 2, NULL, 150000, 2000, 'VALIDEE'); -- Marie Curie retire 150k

INSERT INTO mouvements_comptes (operation_id, compte_id, sens, montant, solde_avant, solde_apres) VALUES
    (1, 1, 'DEBIT', 50500, 1500000, 1449500),
    (2, 2, 'DEBIT', 152000, 500000, 348000);

-- TRANSFERTS INTERNES (génère des frais de transfert normaux sans commissions externes)
INSERT INTO operations (id, reference, type_operation_id, compte_source_id, compte_destination_id, montant, frais, statut) VALUES
    (3, 'OP-TR-INT-001', 3, 1, 2, 100000, 500, 'VALIDEE'), -- Jean -> Marie (100k)
    (4, 'OP-TR-INT-002', 3, 2, 3, 25000, 200, 'VALIDEE'); -- Marie -> Alan (25k)

INSERT INTO mouvements_comptes (operation_id, compte_id, sens, montant, solde_avant, solde_apres) VALUES
    (3, 1, 'DEBIT', 100500, 1449500, 1349000),
    (3, 2, 'CREDIT', 100000, 348000, 448000),
    (4, 2, 'DEBIT', 25200, 448000, 422800),
    (4, 3, 'CREDIT', 25000, 25000, 50000);

-- TRANSFERTS EXTERNES (Vers autres opérateurs)

-- -> Vers Airtel (1.5% comm)
INSERT INTO operations (id, reference, type_operation_id, compte_source_id, compte_destination_id, montant, frais, statut) VALUES
    (5, 'OP-TR-AIR-001', 3, 1, 4, 60000, 500, 'VALIDEE'), -- Frais 500, Comm 900 -> Net: -400
    (6, 'OP-TR-AIR-002', 3, 1, 4, 150000, 1000, 'VALIDEE'); -- Frais 1000, Comm 2250 -> Net: -1250

INSERT INTO mouvements_comptes (operation_id, compte_id, sens, montant, solde_avant, solde_apres) VALUES
    (5, 1, 'DEBIT', 60500, 1349000, 1288500),
    (5, 4, 'CREDIT', 60000, 0, 60000),
    (6, 1, 'DEBIT', 151000, 1288500, 1137500),
    (6, 4, 'CREDIT', 150000, 60000, 210000);

-- -> Vers Orange (2.0% comm)
INSERT INTO operations (id, reference, type_operation_id, compte_source_id, compte_destination_id, montant, frais, statut) VALUES
    (7, 'OP-TR-ORA-001', 3, 1, 5, 200000, 1000, 'VALIDEE'), -- Frais 1000, Comm 4000 -> Net: -3000
    (8, 'OP-TR-ORA-002', 3, 2, 6, 8000, 50, 'VALIDEE'); -- Frais 50, Comm 160 -> Net: -110

INSERT INTO mouvements_comptes (operation_id, compte_id, sens, montant, solde_avant, solde_apres) VALUES
    (7, 1, 'DEBIT', 201000, 1137500, 936500),
    (7, 5, 'CREDIT', 200000, 0, 200000),
    (8, 2, 'DEBIT', 8050, 422800, 414750),
    (8, 6, 'CREDIT', 8000, 0, 8000);

-- -> Vers Mvola (1.0% comm)
INSERT INTO operations (id, reference, type_operation_id, compte_source_id, compte_destination_id, montant, frais, statut) VALUES
    (9, 'OP-TR-MVO-001', 3, 2, 7, 120000, 1000, 'VALIDEE'); -- Frais 1000, Comm 1200 -> Net: -200

INSERT INTO mouvements_comptes (operation_id, compte_id, sens, montant, solde_avant, solde_apres) VALUES
    (9, 2, 'DEBIT', 121000, 414750, 293750),
    (9, 7, 'CREDIT', 120000, 0, 120000);

-- MISE A JOUR DES SOLDES (juste pour être sûr que la DB est propre)
UPDATE comptes SET solde = 936500 WHERE id = 1;
UPDATE comptes SET solde = 293750 WHERE id = 2;
UPDATE comptes SET solde = 50000 WHERE id = 3;
UPDATE comptes SET solde = 210000 WHERE id = 4;
UPDATE comptes SET solde = 200000 WHERE id = 5;
UPDATE comptes SET solde = 8000 WHERE id = 6;
UPDATE comptes SET solde = 120000 WHERE id = 7;
