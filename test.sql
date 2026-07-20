-- Active: 1784550262220@@127.0.0.1@3306
-- ============================================================
-- test.sql — Données de test pour la séparation des gains (V2)
-- ============================================================
-- À exécuter APRÈS base.sql (qui crée les tables et insère
-- les données de base : clients 1-3, comptes 1-3, opérations 1-3,
-- Airtel Money, Orange Money, préfixes 032 et 031).
-- ============================================================

-- ------------------------------------------------------------
-- 7. Clients "externes" (préfixes rattachés à d'autres opérateurs)
-- ------------------------------------------------------------
-- 032... → Airtel Money   (commission 1.5 %)
-- 031... → Orange Money   (commission 2.0 %)

INSERT INTO clients (telephone, nom) VALUES
    ('0321234567', 'Client Airtel Test'),
    ('0317654321', 'Client Orange Test');

-- ------------------------------------------------------------
-- 8. Comptes associés aux clients externes
-- ------------------------------------------------------------
-- Les soldes de départ : on leur crédite assez pour recevoir.
INSERT INTO comptes (client_id, solde) VALUES
    (4, 0),   -- compte du client Airtel (client_id = 4)
    (5, 0);   -- compte du client Orange (client_id = 5)

-- ------------------------------------------------------------
-- 9. Transferts INTERNES supplémentaires
--    (Jean Dupont → Alan Turing, préfixe 037 = notre réseau)
-- ------------------------------------------------------------
-- REF-004 : 25 000 Ar, frais 200 Ar (tranche 10 001–25 000)
INSERT INTO operations
    (reference, type_operation_id, compte_source_id, compte_destination_id, montant, frais, statut)
VALUES
    ('REF-004', 3, 1, 3, 25000, 200, 'VALIDEE');

INSERT INTO mouvements_comptes
    (operation_id, compte_id, sens, montant, solde_avant, solde_apres)
VALUES
    (4, 1, 'DEBIT',  25200, 500000, 474800),
    (4, 3, 'CREDIT', 25000,  20000,  45000);

-- ------------------------------------------------------------
-- 10. Transferts EXTERNES vers Airtel Money (préfixe 032)
-- ------------------------------------------------------------
-- REF-005 : 20 000 Ar transférés vers 0321234567, frais 200 Ar
--   → commission Airtel = 20 000 × 1.5 % = 300 Ar
--   → gain net opérateur sur cette op = 200 − 300 = −100 Ar
INSERT INTO operations
    (reference, type_operation_id, compte_source_id, compte_destination_id, montant, frais, statut)
VALUES
    ('REF-005', 3, 1, 4, 20000, 200, 'VALIDEE');

INSERT INTO mouvements_comptes
    (operation_id, compte_id, sens, montant, solde_avant, solde_apres)
VALUES
    (5, 1, 'DEBIT',  20200, 474800, 454600),
    (5, 4, 'CREDIT', 20000,      0,  20000);

-- REF-006 : 50 000 Ar transférés vers 0321234567, frais 400 Ar
--   → commission Airtel = 50 000 × 1.5 % = 750 Ar
--   → gain net opérateur sur cette op = 400 − 750 = −350 Ar
INSERT INTO operations
    (reference, type_operation_id, compte_source_id, compte_destination_id, montant, frais, statut)
VALUES
    ('REF-006', 3, 2, 4, 50000, 400, 'VALIDEE');

INSERT INTO mouvements_comptes
    (operation_id, compte_id, sens, montant, solde_avant, solde_apres)
VALUES
    (6, 2, 'DEBIT',  50400, 150000,  99600),
    (6, 4, 'CREDIT', 50000,  20000,  70000);

-- ------------------------------------------------------------
-- 11. Transferts EXTERNES vers Orange Money (préfixe 031)
-- ------------------------------------------------------------
-- REF-007 : 10 000 Ar transférés vers 0317654321, frais 100 Ar
--   → commission Orange = 10 000 × 2.0 % = 200 Ar
--   → gain net opérateur sur cette op = 100 − 200 = −100 Ar
INSERT INTO operations
    (reference, type_operation_id, compte_source_id, compte_destination_id, montant, frais, statut)
VALUES
    ('REF-007', 3, 1, 5, 10000, 100, 'VALIDEE');

INSERT INTO mouvements_comptes
    (operation_id, compte_id, sens, montant, solde_avant, solde_apres)
VALUES
    (7, 1, 'DEBIT',  10100, 454600, 444500),
    (7, 5, 'CREDIT', 10000,      0,  10000);

-- REF-008 : 100 000 Ar transférés vers 0317654321, frais 800 Ar
--   → commission Orange = 100 000 × 2.0 % = 2 000 Ar
--   → gain net opérateur sur cette op = 800 − 2 000 = −1 200 Ar
INSERT INTO operations
    (reference, type_operation_id, compte_source_id, compte_destination_id, montant, frais, statut)
VALUES
    ('REF-008', 3, 1, 5, 100000, 800, 'VALIDEE');

INSERT INTO mouvements_comptes
    (operation_id, compte_id, sens, montant, solde_avant, solde_apres)
VALUES
    (8, 1, 'DEBIT',  100800, 444500, 343700),
    (8, 5, 'CREDIT', 100000,  10000, 110000);

-- ------------------------------------------------------------
-- 12. Retrait supplémentaire (pour enrichir les frais de retrait)
-- ------------------------------------------------------------
-- REF-009 : retrait de 25 000 Ar depuis le compte 3 (Alan Turing)
--   → frais = 200 Ar (tranche 10 001–25 000)
INSERT INTO operations
    (reference, type_operation_id, compte_source_id, compte_destination_id, montant, frais, statut)
VALUES
    ('REF-009', 2, 3, NULL, 25000, 200, 'VALIDEE');

INSERT INTO mouvements_comptes
    (operation_id, compte_id, sens, montant, solde_avant, solde_apres)
VALUES
    (9, 3, 'DEBIT', 25200, 45000, 19800);

-- ============================================================
-- RÉSULTATS ATTENDUS sur /gains
-- ============================================================
--
-- ┌──────────────────────────────┬──────────────────────────────┐
-- │ Frais de retrait             │ 800 + 200 = 1 000 Ar         │
-- │ Transferts internes          │ 100 + 200 = 300 Ar           │
-- │ Commissions dues             │                              │
-- │   Airtel (70 000 × 1.5%)    │ 1 050 Ar                     │
-- │   Orange (110 000 × 2.0%)   │ 2 200 Ar                     │
-- │   Total commissions          │ 3 250 Ar                     │
-- │ Frais ext. collectés         │ (200+400) + (100+800) = 1500 │
-- │ Gain net ext.                │ 1 500 − 3 250 = −1 750 Ar   │
-- │ GAIN NET TOTAL               │ 1 000 + 300 + (−1 750)       │
-- │                              │ = −450 Ar (commissons > frais)│
-- └──────────────────────────────┴──────────────────────────────┘
--
-- ============================================================
-- RÉSULTATS ATTENDUS sur /reglements
-- ============================================================
-- La commission se calcule uniquement sur le montant envoyé.
-- Total à régler = montant envoyé + commission.
-- Frais de retrait = 0 (pas de frais de retrait pour les autres opérateurs).
--
-- ┌─────────────┬──────────┬───────────┬─────────────┬────────────────┐
-- │ Opérateur   │ Nb ops   │ Montant   │ Commission  │ Total à régler │
-- ├─────────────┼──────────┼───────────┼─────────────┼────────────────┤
-- │ Airtel Money│ 2        │ 70 000    │ 1 050       │ 71 050 Ar      │
-- │ Orange Money│ 2        │ 110 000   │ 2 200       │ 112 200 Ar     │
-- ├─────────────┼──────────┼───────────┼─────────────┼────────────────┤
-- │ TOTAL       │ 4        │ 180 000   │ 3 250       │ 183 250 Ar     │
-- └─────────────┴──────────┴───────────┴─────────────┴────────────────┘
-- ============================================================
