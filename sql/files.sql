USE webproject;

CREATE TABLE files (
    id int AUTO_INCREMENT NOT NULL,
    created_by int NOT NULL,
    path VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    size int NOT NULL,
    uploaded_on DATETIME NOT NULL,
    last_changed DATETIME NOT NULL,
    type int NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY (created_by) REFERENCES accounts(id),
    FOREIGN KEY (type) REFERENCES types(id)
)


USE webproject;
CREATE TABLE types (
    id int AUTO_INCREMENT NOT NULL,
    name VARCHAR(255) NOT NULL,
    extension VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
)