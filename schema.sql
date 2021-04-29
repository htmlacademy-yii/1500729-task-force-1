DROP DATABASE taskforce;

CREATE DATABASE taskforce
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE UTF8_GENERAL_CI;

USE taskforce;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    email VARCHAR(64) NOT NULL UNIQUE,
    name VARCHAR (64) NOT NULL,
    userpick VARCHAR(128),
    information TEXT(500),
    birthday TIMESTAMP,
    location_id INT,
    password VARCHAR(128) NOT NULL,
    phone VARCHAR (32),
    skype VARCHAR (32),
    telegram VARCHAR (32),
    dt_last_action TIMESTAMP,
    show_profile TINYINT(1) DEFAULT 1,
    show_contacts TINYINT(1) DEFAULT 0,
    new_message TINYINT(1) DEFAULT 1,
    new_action TINYINT(1) DEFAULT 1,
    new_review TINYINT(1) DEFAULT 1,
    failed_tasks INT,
    done_tasks INT,
    role TINYINT(1) DEFAULT 0,
    FOREIGN KEY (location_id) REFERENCES locations (id)
);

CREATE TABLE locations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    city VARCHAR(64) NOT NULL
);

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    title VARCHAR(128) NOT NULL,
    description VARCHAR (256) NOT NULL,
    category_id INT NOT NULL,
    author_id INT NOT NULL,
    city_id INT,
    location_id VARCHAR(64),
    budget INT,
    due_date TIMESTAMP,
    status VARCHAR(32) DEFAULT 'new',
    executor_id INT,
    FOREIGN KEY (author_id) REFERENCES users (id),
    FOREIGN KEY (executor_id) REFERENCES users (id),
    FOREIGN KEY (city_id) REFERENCES locations(id),
    FOREIGN KEY (category_id) REFERENCES categories(id),
    FOREIGN KEY (status) REFERENCES statuses(name)
);

CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    content VARCHAR(256),
    status VARCHAR(32),
    task_id INT NOT NULL,
    ratio INT NOT NULL,
    FOREIGN KEY (task_id) REFERENCES tasks (id),
    FOREIGN KEY (status) REFERENCES tasks(status)
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR (32) NOT NULL UNIQUE
);

CREATE TABLE task_files (
    id INT AUTO_INCREMENT PRIMARY KEY,
    path VARCHAR (128) NOT NULL,
    task_id INT NOT NULL,
    FOREIGN KEY (task_id) REFERENCES tasks (id)
);

CREATE TABLE executor_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users (id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE work_photos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    path VARCHAR(128) NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users (id)
);

CREATE TABLE statuses (
    name VARCHAR (32) PRIMARY KEY
);

CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    content TEXT(500) NOT NULL,
    dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    task_id INT NOT NULL,
    executor_id INT NOT NULL,
    author_id INT NOT NULL,
    FOREIGN KEY (task_id) REFERENCES tasks (id),
    FOREIGN KEY (executor_id) REFERENCES users (id),
    FOREIGN KEY (author_id) REFERENCES users (id)
);

CREATE TABLE responds (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    budget INT NOT NULL,
    content VARCHAR(256),
    task_id INT NOT NULL,
    executor_id INT NOT NULL,
    FOREIGN KEY (task_id) REFERENCES tasks (id),
    FOREIGN KEY (executor_id) REFERENCES users(id)
);
