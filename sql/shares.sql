USE webproject;

CREATE TABLE shares (
    id int AUTO_INCREMENT NOT NULL,
    shared_by int NOT NULL,
    shared_to int NOT NULL,
    file_name varchar(255) NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY (shared_by) REFERENCES accounts(id),
    FOREIGN KEY (shared_to) REFERENCES accounts(id)
)
