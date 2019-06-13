USE webproject;

CREATE TABLE accounts (
    id int AUTO_INCREMENT NOT NULL,
    username varchar(255) NOT NULL,
    password varchar(2056) NOT NULL,
    email varchar(255) NOT NULL UNIQUE,
    PRIMARY KEY (id)
)