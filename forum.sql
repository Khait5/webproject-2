CREATE TABLE IF NOT EXISTS forum_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    parent_id INT DEFAULT NULL,
    is_admin_only TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES forum_categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS forum_topics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(45) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES forum_categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS forum_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    topic_id INT NOT NULL,
    content TEXT NOT NULL,
    author VARCHAR(45) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (topic_id) REFERENCES forum_topics(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Note: We are linking `author` to `accounts(login)`. For Lineage 2 servers, creating a hard foreign key
-- to the `accounts` table might fail if the server logic manages accounts differently, but logically it's linked.
-- A foreign key to `accounts` `login` is omitted intentionally to prevent strict constraint failures if the game server deletes accounts without web panel knowledge, but the logical link exists.

-- Insert root categories
INSERT INTO forum_categories (name, is_admin_only) VALUES ('Administracion', 1);
SET @admin_id = LAST_INSERT_ID();

INSERT INTO forum_categories (name, is_admin_only) VALUES ('Foro de ayuda', 0);
SET @help_id = LAST_INSERT_ID();

-- Insert subcategories for Administracion
INSERT INTO forum_categories (name, parent_id, is_admin_only) VALUES ('Noticias', @admin_id, 1);
INSERT INTO forum_categories (name, parent_id, is_admin_only) VALUES ('Notas del parche', @admin_id, 1);

-- Insert subcategories for Foro de ayuda
INSERT INTO forum_categories (name, parent_id, is_admin_only) VALUES ('Misiones', @help_id, 0);
INSERT INTO forum_categories (name, parent_id, is_admin_only) VALUES ('Guias', @help_id, 0);
INSERT INTO forum_categories (name, parent_id, is_admin_only) VALUES ('Dudas y soporte', @help_id, 0);
