DROP VIEW IF EXISTS vue_gains_operateur;
DROP VIEW IF EXISTS vue_situation_comptes;

DROP TABLE IF EXISTS mouvements_comptes;
DROP TABLE IF EXISTS operations;
DROP TABLE IF EXISTS baremes_frais;
DROP TABLE IF EXISTS types_operations;
DROP TABLE IF EXISTS comptes;
DROP TABLE IF EXISTS clients;
DROP TABLE IF EXISTS prefixes_operateur;

CREATE TABLE prefixes_operateur (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe TEXT NOT NULL UNIQUE,
    actif INTEGER NOT NULL DEFAULT 1,
    nom_operateur TEXT,
    est_interne INTEGER NOT NULL DEFAULT 0,
    commission_externe INTEGER NOT NULL DEFAULT 0,
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
    destinataire_telephone TEXT,
    operateur_destination TEXT,
    transfert_externe INTEGER NOT NULL DEFAULT 0,
    frais_transfert INTEGER NOT NULL DEFAULT 0,
    commission_externe INTEGER NOT NULL DEFAULT 0,
    frais_retrait_inclus INTEGER NOT NULL DEFAULT 0,
    groupe_reference TEXT,
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

INSERT INTO prefixes_operateur (
    prefixe,
    nom_operateur,
    est_interne,
    commission_externe
)
VALUES
    ('033', 'MobiCash', 1, 0),
    ('037', 'Opérateur partenaire', 0, 100);

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

CREATE Table prommotion(
    id INTEGER PRIMARY key AUTOINCREMENT,
    libelle TEXT,
    valeur INT NOT NULL DEFAULT 0
)