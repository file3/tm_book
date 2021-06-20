CREATE DATABASE transfermate WITH ENCODING='UTF8';
\c transfermate;

DROP TABLE books;
DROP TABLE authors;

CREATE TABLE authors
(
    auid        SERIAL  NOT NULL PRIMARY KEY,
    name        TEXT    NOT NULL UNIQUE
);

CREATE TABLE books
(
    boid        SERIAL  NOT NULL PRIMARY KEY,
    author      INT     NOT NULL REFERENCES authors ON DELETE CASCADE,  -- auid
    name        TEXT    NOT NULL,
    path        TEXT    NOT NULL UNIQUE
);

-- SELECT NEXTVAL('authors_auid_seq');
INSERT INTO authors (name) VALUES ('3');
INSERT INTO books (author, name, path) VALUES (1, 'Standard', '/home/fattila/_projects/zz_work/transfermate/data/book2/index.xml');

-- SET @nextval = NEXTVAL('authors_auid_seq');
-- INSERT INTO authors (auid, name) VALUES (@nextval, '3');
-- INSERT INTO books (author, name, path) VALUES (@nextval, 'Standard', '/home/fattila/_projects/zz_work/transfermate/data/book2/index.xml');
