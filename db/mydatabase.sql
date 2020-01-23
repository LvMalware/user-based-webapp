-- As SQLite don't have a native type to handle dates, we will use a TEXT field
CREATE TABLE IF NOT EXISTS users
(
    `id`         INTEGER PRIMARY KEY AUTOINCREMENT,
    `first_name` TEXT NOT NULL,
    `last_name`  TEXT NOT NULL,
    `email`      TEXT NOT NULL UNIQUE,
    `username`   TEXT NOT NULL UNIQUE,
    `password`   TEXT NOT NULL,
    `security_q` TEXT NOT NULL,
    `answer_q`   TEXT NOT NULL,
    `last_login` TEXT NOT NULL
);