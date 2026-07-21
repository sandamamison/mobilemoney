-- Active: 1784555908051@@127.0.0.1@3306
DROP VIEW IF EXISTS vue_gains_operateur;
DROP VIEW IF EXISTS vue_situation_comptes;

DROP TABLE IF EXISTS mouvements_comptes;
DROP TABLE IF EXISTS operations;
DROP TABLE IF EXISTS baremes_frais;
DROP TABLE IF EXISTS types_operations;
DROP TABLE IF EXISTS comptes;
DROP TABLE IF EXISTS clients;
DROP TABLE IF EXISTS prefixes_operateur;
DROP TABLE IF EXISTS prefixes_autres_operateurs;
DROP TABLE IF EXISTS autres_operateurs;

CREATE TABLE autres_operateurs (
    id         INTEGER PRIMARY KEY AUTOINCREMENT,
    nom        TEXT    NOT NULL UNIQUE,
    commission REAL    NOT NULL DEFAULT 0 CHECK (commission >= 0 AND commission <= 100),
    actif      INTEGER NOT NULL DEFAULT 1,
    date_creation TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE prefixes_autres_operateurs (
    id                   INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe              TEXT    NOT NULL UNIQUE,
    autre_operateur_id   INTEGER NOT NULL,
    actif                INTEGER NOT NULL DEFAULT 1,
    date_creation        TEXT    NOT NULL DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (autre_operateur_id)
        REFERENCES autres_operateurs(id)
        ON DELETE CASCADE
);


CREATE TABLE prefixes_operateur (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe TEXT NOT NULL UNIQUE,
    actif INTEGER NOT NULL DEFAULT 1,
    date_creation TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE clients (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    telephone TEXT NOT NULL UNIQUE,
    nom TEXT,
    statut TEXT NOT NULL DEFAULT 'ACTIF',
    date_creation TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE comptes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    client_id INTEGER NOT NULL UNIQUE,
    solde INTEGER NOT NULL DEFAULT 0,
    statut TEXT NOT NULL DEFAULT 'ACTIF',
    date_creation TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (client_id)
        REFERENCES clients(id)
        ON DELETE CASCADE
);

CREATE TABLE types_operations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    code TEXT NOT NULL UNIQUE,
    libelle TEXT NOT NULL,
    avec_frais INTEGER NOT NULL DEFAULT 0,
    actif INTEGER NOT NULL DEFAULT 1
);

CREATE TABLE baremes_frais (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    type_operation_id INTEGER NOT NULL,
    montant_min INTEGER NOT NULL,
    montant_max INTEGER NOT NULL,
    frais INTEGER NOT NULL DEFAULT 0,
    actif INTEGER NOT NULL DEFAULT 1,

    FOREIGN KEY (type_operation_id)
        REFERENCES types_operations(id)
        ON DELETE CASCADE
);

CREATE TABLE operations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    reference TEXT NOT NULL UNIQUE,
    type_operation_id INTEGER NOT NULL,
    compte_source_id INTEGER,
    compte_destination_id INTEGER,
    montant INTEGER NOT NULL,
    frais INTEGER NOT NULL DEFAULT 0,
    statut TEXT NOT NULL DEFAULT 'VALIDEE',
    date_operation TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (type_operation_id)
        REFERENCES types_operations(id),

    FOREIGN KEY (compte_source_id)
        REFERENCES comptes(id),

    FOREIGN KEY (compte_destination_id)
        REFERENCES comptes(id)
);

CREATE TABLE mouvements_comptes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    operation_id INTEGER NOT NULL,
    compte_id INTEGER NOT NULL,
    sens TEXT NOT NULL,
    montant INTEGER NOT NULL,
    solde_avant INTEGER NOT NULL,
    solde_apres INTEGER NOT NULL,
    date_mouvement TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (operation_id)
        REFERENCES operations(id)
        ON DELETE CASCADE,

    FOREIGN KEY (compte_id)
        REFERENCES comptes(id)
        ON DELETE CASCADE
);

CREATE INDEX index_client_telephone
ON clients(telephone);

CREATE INDEX index_operation_date
ON operations(date_operation);

CREATE INDEX index_mouvement_compte
ON mouvements_comptes(compte_id);

CREATE VIEW vue_situation_comptes AS
SELECT
    clients.id AS client_id,
    clients.telephone,
    clients.nom,
    clients.statut AS statut_client,
    comptes.id AS compte_id,
    comptes.solde,
    comptes.statut AS statut_compte,
    comptes.date_creation
FROM clients
JOIN comptes
    ON comptes.client_id = clients.id;

CREATE VIEW vue_gains_operateur AS
SELECT
    types_operations.code,
    types_operations.libelle,
    COUNT(operations.id) AS nombre_operations,
    COALESCE(SUM(operations.frais), 0) AS gain_total
FROM types_operations
LEFT JOIN operations
    ON operations.type_operation_id = types_operations.id
    AND operations.statut = 'VALIDEE'
WHERE types_operations.code IN ('RETRAIT', 'TRANSFERT')
GROUP BY
    types_operations.id,
    types_operations.code,
    types_operations.libelle;

INSERT INTO prefixes_operateur (prefixe)
VALUES
    ('033'),
    ('037');

INSERT INTO types_operations (
    code,
    libelle,
    avec_frais
)
VALUES
    ('DEPOT', 'Dépôt', 0),
    ('RETRAIT', 'Retrait', 1),
    ('TRANSFERT', 'Transfert', 1);

WITH tranches (
    montant_min,
    montant_max,
    frais
) AS (
    VALUES
        (100, 1000, 50),
        (1001, 5000, 50),
        (5001, 10000, 100),
        (10001, 25000, 200),
        (25001, 50000, 400),
        (50001, 100000, 800),
        (100001, 250000, 1500),
        (250001, 500000, 1500),
        (500001, 1000000, 2500),
        (1000001, 2000000, 3000)
)
INSERT INTO baremes_frais (
    type_operation_id,
    montant_min,
    montant_max,
    frais
)
SELECT
    types_operations.id,
    tranches.montant_min,
    tranches.montant_max,
    tranches.frais
FROM types_operations
CROSS JOIN tranches
WHERE types_operations.code IN ('RETRAIT', 'TRANSFERT');

-- ==========================================
-- JEU DE DONNÉES POUR TESTER LE DASHBOARD
-- ==========================================

-- 1. Création de clients
INSERT INTO clients (telephone, nom) VALUES
('0341122233', 'Jean Dupont'),
('0334455566', 'Marie Curie'),
('0377788899', 'Alan Turing');

-- 2. Création de comptes associés
INSERT INTO comptes (client_id, solde) VALUES
(1, 500000),
(2, 150000),
(3, 20000);

-- 3. Création de quelques opérations 
-- id 1 = DEPOT, id 2 = RETRAIT, id 3 = TRANSFERT
INSERT INTO operations (reference, type_operation_id, compte_source_id, compte_destination_id, montant, frais, statut) VALUES
('REF-001', 1, NULL, 1, 100000, 0, 'VALIDEE'), -- Dépôt sur le compte 1
('REF-002', 2, 1, NULL, 50000, 800, 'VALIDEE'), -- Retrait depuis le compte 1 (Frais: 800)
('REF-003', 3, 2, 3, 10000, 100, 'VALIDEE'); -- Transfert du compte 2 vers le compte 3 (Frais: 100)

-- 4. Création des mouvements de comptes liés aux opérations
INSERT INTO mouvements_comptes (operation_id, compte_id, sens, montant, solde_avant, solde_apres) VALUES
(1, 1, 'CREDIT', 100000, 400000, 500000),
(2, 1, 'DEBIT', 50800, 550800, 500000),
(3, 2, 'DEBIT', 10100, 160100, 150000),
(3, 3, 'CREDIT', 10000, 10000, 20000);

-- 5. Autres opérateurs (données de démonstration)
INSERT INTO autres_operateurs (nom, commission, actif) VALUES
('Airtel Money', 1.50, 1),
('Orange Money', 2.00, 1);

-- 6. Préfixes externes (données de démonstration)
-- Les IDs ci-dessous supposent qu'Airtel Money = 1, Orange Money = 2
INSERT INTO prefixes_autres_operateurs (prefixe, autre_operateur_id, actif) VALUES
('032', 1, 1),
('031', 2, 1);