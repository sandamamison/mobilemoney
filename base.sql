CREATE TABLE prefixes_operateur(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe TEXT,
    actif INTEGER NOT NULL DEFAULT 1,
    date_creation TEXT DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE clients(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    telephone TEXT NOT NULL UNIQUE,
    nom TEXT,
    statut TEXT NOT NULL DEFAULT 1,
    date_creation TEXT DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE comptes(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    client_id INTEGER NOT NULL UNIQUE,
    solde INTEGER NOT NULL DEFAULT 0,
    statut INTEGER NOT NULL DEFAULT 1,
    date_creation TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE
);

CREATE TABLE types_operations(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    code TEXT NOT NULL UNIQUE,
    libelle TEXT NOT NULL,
    avec_frais INTEGER NOT NULL DEFAULT 0,
    actif INTEGER NOT NULL DEFAULT 1
);

CREATE TABLE baremes_frais(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    type_operation_id INTEGER NOT NULL,
    montant_min INTEGER NOT NULL DEFAULT 0,
    montant_max INTEGER NOT NULL,
    frais INTEGER NOT NULL,
    actif INTEGER NOT NULL DEFAULT 1,
    FOREIGN KEY (type_operation_id) REFERENCES types_operations(id) ON DELETE CASCADE
);

CREATE TABLE operations(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    reference TEXT,
    type_operation_id INTEGER,
    compte_source_id INTEGER,
    compte_destination_id INTEGER,
    montant INTEGER,
    frais INTEGER,
    statut TEXT,
    date_operation TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (type_operation_id) REFERENCES types_operations(id) ON DELETE SET NULL,
    FOREIGN KEY (compte_source_id) REFERENCES comptes(id) ON DELETE SET NULL,
    FOREIGN KEY (compte_destination_id) REFERENCES comptes(id) ON DELETE SET NULL
);

CREATE TABLE mouvements_comptes(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    operation_id INTEGER,
    compte_id INTEGER,
    sens TEXT,
    montant INTEGER,
    solde_avant INTEGER,
    solde_apres INTEGER,
    date_mouvement TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (operation_id) REFERENCES operations(id) ON DELETE CASCADE,
    FOREIGN KEY (compte_id) REFERENCES comptes(id) ON DELETE CASCADE
);
