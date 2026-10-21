-- Database schema for mvc_login_demo (PostgreSQL)

CREATE TABLE IF NOT EXISTS user_login (
    uid INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    hash CHAR(64) NULL DEFAULT NULL
);

-- Username is unique without case, Admin and admin are the same user
CREATE UNIQUE INDEX IF NOT EXISTS user_login_username_lower ON user_login (lower(username));

-- group: 1 = Member, 2 = Admin
CREATE TABLE IF NOT EXISTS users (
    uid INTEGER NOT NULL PRIMARY KEY REFERENCES user_login (uid) ON DELETE CASCADE,
    firstname VARCHAR(100) NOT NULL DEFAULT '',
    surname VARCHAR(100) NOT NULL DEFAULT '',
    email VARCHAR(255) NOT NULL DEFAULT '',
    gender VARCHAR(10) NOT NULL DEFAULT '',
    "group" SMALLINT NOT NULL DEFAULT 1
);
