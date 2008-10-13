DROP TABLE IF EXISTS `users`;

CREATE TABLE users (id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, nickname VARCHAR(255), role VARCHAR(255), email VARCHAR(255), fullname VARCHAR(255), dob DATE, postcode VARCHAR(24), country VARCHAR(2), language VARCHAR(2), timezone VARCHAR(24), github_user VARCHAR(64), paypal_user VARCHAR(255), login_at DATETIME, login_ip VARCHAR(15), latitude DOUBLE, longitude DOUBLE, created_at DATETIME, updated_at DATETIME, deleted TINYINT(1) DEFAULT 0 NOT NULL, PRIMARY KEY(id)) ENGINE = INNODB;

DROP TABLE IF EXISTS `user_tokens`;

CREATE TABLE user_tokens (id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, user_id BIGINT UNSIGNED NOT NULL, token VARCHAR(32) UNIQUE, ip VARCHAR(12), created_at DATETIME, updated_at DATETIME, INDEX user_id_idx (user_id), PRIMARY KEY(id)) ENGINE = INNODB;

DROP TABLE IF EXISTS `user_ids`;

CREATE TABLE user_ids (id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, user_id BIGINT UNSIGNED NOT NULL, url VARCHAR(255) UNIQUE, INDEX user_id_idx (user_id), PRIMARY KEY(id)) ENGINE = INNODB;

DROP TABLE IF EXISTS `tags`;

CREATE TABLE tags (id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, count BIGINT UNSIGNED DEFAULT 0, word VARCHAR(255) UNIQUE, PRIMARY KEY(id)) ENGINE = INNODB;

DROP TABLE IF EXISTS `stacks`;

CREATE TABLE stacks (id BIGINT AUTO_INCREMENT, user_id BIGINT UNSIGNED NOT NULL, resource_id BIGINT UNSIGNED NOT NULL, created_at DATETIME, updated_at DATETIME, INDEX user_id_idx (user_id), INDEX resource_id_idx (resource_id), PRIMARY KEY(id)) ENGINE = INNODB;

DROP TABLE IF EXISTS `reviews`;

CREATE TABLE reviews (id BIGINT AUTO_INCREMENT, user_id BIGINT UNSIGNED NOT NULL, resource_id BIGINT UNSIGNED NOT NULL, rating TINYINT UNSIGNED NOT NULL, title VARCHAR(50) NOT NULL, text TEXT NOT NULL, created_at DATETIME, updated_at DATETIME, INDEX user_id_idx (user_id), INDEX resource_id_idx (resource_id), PRIMARY KEY(id)) ENGINE = INNODB;

DROP TABLE IF EXISTS `resource_tag_ref`;

CREATE TABLE resource_tag_ref (tag_id BIGINT UNSIGNED NOT NULL, resource_id BIGINT UNSIGNED NOT NULL, INDEX tag_id_idx (tag_id), INDEX resource_id_idx (resource_id), PRIMARY KEY(tag_id, resource_id)) ENGINE = INNODB;

DROP TABLE IF EXISTS `resource_child_model`;

CREATE TABLE resource_child_model (resource_id BIGINT UNSIGNED NOT NULL, PRIMARY KEY(resource_id)) ENGINE = INNODB;

DROP TABLE IF EXISTS `resource`;

CREATE TABLE resource (id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, user_id BIGINT UNSIGNED, claimed TINYINT(1), author VARCHAR(255), ident VARCHAR(32) NOT NULL UNIQUE, title VARCHAR(50), text TEXT, url_feed VARCHAR(255), url_repository VARCHAR(255), license_url VARCHAR(255), license_text VARCHAR(255), type TINYINT UNSIGNED NOT NULL, views BIGINT UNSIGNED DEFAULT 0 NOT NULL, rating TINYINT UNSIGNED DEFAULT 0 NOT NULL, status TINYINT UNSIGNED DEFAULT 0 NOT NULL, core_min VARCHAR(16), core_max VARCHAR(16), created_at DATETIME, updated_at DATETIME, deleted TINYINT(1) DEFAULT 0 NOT NULL, INDEX type_idx (type), INDEX status_idx (status), INDEX user_id_idx (user_id), PRIMARY KEY(id)) ENGINE = INNODB;

DROP TABLE IF EXISTS `releases`;

CREATE TABLE releases (id BIGINT AUTO_INCREMENT, user_id BIGINT UNSIGNED NOT NULL, resource_id BIGINT UNSIGNED NOT NULL, version VARCHAR(50), stage TINYINT UNSIGNED NOT NULL, url VARCHAR(255), text TEXT, created_at DATETIME, updated_at DATETIME, INDEX user_id_idx (user_id), INDEX resource_id_idx (resource_id), PRIMARY KEY(id)) ENGINE = INNODB;

DROP TABLE IF EXISTS `logs`;

CREATE TABLE logs (id BIGINT AUTO_INCREMENT, user_id BIGINT UNSIGNED NOT NULL, title VARCHAR(50), text TEXT, priority TINYINT UNSIGNED NOT NULL, status TINYINT UNSIGNED NOT NULL, created_at DATETIME, updated_at DATETIME, INDEX user_id_idx (user_id), PRIMARY KEY(id)) ENGINE = INNODB;

DROP TABLE IF EXISTS `links`;

CREATE TABLE links (id BIGINT AUTO_INCREMENT, user_id BIGINT UNSIGNED NOT NULL, resource_id BIGINT UNSIGNED NOT NULL, title VARCHAR(50), url VARCHAR(255), text TEXT, type TINYINT UNSIGNED DEFAULT 0 NOT NULL, priority TINYINT UNSIGNED DEFAULT 0 NOT NULL, created_at DATETIME, updated_at DATETIME, INDEX user_id_idx (user_id), INDEX resource_id_idx (resource_id), PRIMARY KEY(id)) ENGINE = INNODB;

DROP TABLE IF EXISTS `dependencies`;

CREATE TABLE dependencies (id BIGINT AUTO_INCREMENT, user_id BIGINT UNSIGNED NOT NULL, resource_id BIGINT UNSIGNED, paths VARCHAR(255), version VARCHAR(16), version_max VARCHAR(16), url VARCHAR(255), text TEXT, created_at DATETIME, updated_at DATETIME, INDEX user_id_idx (user_id), INDEX resource_id_idx (resource_id), PRIMARY KEY(id)) ENGINE = INNODB;

DROP TABLE IF EXISTS `contributors`;

CREATE TABLE contributors (id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, user_id BIGINT UNSIGNED NOT NULL, resource_id BIGINT UNSIGNED NOT NULL, verified TINYINT(1), title VARCHAR(50), text TEXT, created_at DATETIME, updated_at DATETIME, INDEX user_id_idx (user_id), INDEX resource_id_idx (resource_id), PRIMARY KEY(id)) ENGINE = INNODB;

ALTER TABLE user_tokens ADD FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;

ALTER TABLE user_ids ADD FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;

ALTER TABLE stacks ADD FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;

ALTER TABLE stacks ADD FOREIGN KEY (user_id) REFERENCES resource(id);

ALTER TABLE stacks ADD FOREIGN KEY (resource_id) REFERENCES resource(id) ON DELETE CASCADE;

ALTER TABLE reviews ADD FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;

ALTER TABLE reviews ADD FOREIGN KEY (resource_id) REFERENCES resource(id) ON DELETE CASCADE;

ALTER TABLE resource_tag_ref ADD FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE;

ALTER TABLE resource_tag_ref ADD FOREIGN KEY (resource_id) REFERENCES resource(id) ON DELETE CASCADE;

ALTER TABLE resource ADD FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;

ALTER TABLE releases ADD FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;

ALTER TABLE releases ADD FOREIGN KEY (resource_id) REFERENCES resource(id) ON DELETE CASCADE;

ALTER TABLE logs ADD FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;

ALTER TABLE links ADD FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;

ALTER TABLE links ADD FOREIGN KEY (resource_id) REFERENCES resource(id) ON DELETE CASCADE;

ALTER TABLE dependencies ADD FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;

ALTER TABLE dependencies ADD FOREIGN KEY (resource_id) REFERENCES resource(id) ON DELETE CASCADE;

ALTER TABLE contributors ADD FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;

ALTER TABLE contributors ADD FOREIGN KEY (resource_id) REFERENCES resource(id) ON DELETE CASCADE;