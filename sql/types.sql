USE webproject;

CREATE TABLE types (
    id int AUTO_INCREMENT NOT NULL,
    typename varchar(255) NOT NULL,
    extension varchar(255) NOT NULL
    PRIMARY KEY (id)
)