CREATE TABLE IF NOT EXISTS `user`
( 
    `id`            INT NOT NULL AUTO_INCREMENT, 
    `login`         VARCHAR(160), 
    `password`      VARCHAR(160), 
    `email`         VARCHAR(160), 
    `level`         INT, 
    `dateCreation`  DATETIME,
    PRIMARY KEY (`id`)
) CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `manymany`
( 
    `id`            INT NOT NULL AUTO_INCREMENT, 
    `id1`           INT, 
    `id2`           INT, 
    `table1`        VARCHAR(160), 
    `table2`        VARCHAR(160), 
    `quality`       VARCHAR(160), 
    `quantity`      DECIMAL(10,2), 
    PRIMARY KEY (`id`)
) CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `content`
( 
    `id`                INT NOT NULL AUTO_INCREMENT, 
    `id_user`           INT, 
    `username`          VARCHAR(160),
    `uri`               VARCHAR(160), 
    `title`             VARCHAR(160), 
    `code`              TEXT, 
    `latitude`          DECIMAL(10,6),    
    `longitude`         DECIMAL(10,6),    
    `altitude`          DECIMAL(10,2),    
    `image`             VARCHAR(160), 
    `priority`          INT, 
    `category`          VARCHAR(160), 
    `child`             TEXT, 
    `json`              TEXT, 
    `nbrun`             INT, 
    `dateLastRun`       DATETIME,
    `datePublication`   DATETIME,
    `dateEnd`           DATETIME,
    `status`            VARCHAR(160), 
    `author`            VARCHAR(160), 
    PRIMARY KEY (`id`)
) CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `blocnote`
( 
    `id`                INT NOT NULL AUTO_INCREMENT, 
    `id_user`           INT, 
    `username`          VARCHAR(160),
    `uri`               VARCHAR(160), 
    `title`             VARCHAR(160), 
    `md5`               VARCHAR(160), 
    `code`              TEXT, 
    `latitude`          DECIMAL(10,6),    
    `longitude`         DECIMAL(10,6),    
    `altitude`          DECIMAL(10,2),    
    `nbrun`             INT, 
    `dateLastRun`       DATETIME,
    `image`             VARCHAR(160), 
    `priority`          INT, 
    `category`          VARCHAR(160), 
    `child`             TEXT, 
    `json`              TEXT, 
    `datePublication`   DATETIME,
    `dateEnd`           DATETIME,
    `status`            VARCHAR(160),    
    PRIMARY KEY (`id`)
) CHARSET=utf8mb4;

