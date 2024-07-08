CREATE database IF NOT EXISTS anime_ohayoo;

USE anime_ohayoo;


CREATE table IF NOT EXISTS users(

    id                  int(255) auto_increment not null,
    role                varchar(20),
    name                varchar(100),
    surname             varchar(200),
    email               varchar(255),
    password            varchar(255),
    created_at          datetime,

    CONSTRAINT pk_users PRIMARY KEY (id)  

)ENGINE = InnoDB;

CREATE table IF NOT EXISTS catalogo(

    id                  int(255) auto_increment not null,
    name                varchar(100),
    image_path          varchar(255),
    image_port          varchar(255),
    type                varchar(200),
    puntaje             int(255),
    ruta                varchar(255),
    description         text,
    created_at          datetime,

    CONSTRAINT pk_catalogo PRIMARY KEY (id)
    
) ENGINE = InnoDB;

CREATE table IF NOT EXISTS episode(

    id              int(255) auto_increment not null,
    catalogo_id     int(255) not null,
    name            varchar(100) not null,
    episode         varchar(100),
    ruta            varchar(100),
    acapite         int(255),
    image_port      varchar(100),
    video_path      varchar(255),
    created_at      datetime,

    CONSTRAINT pk_Episode PRIMARY KEY (id),
    CONSTRAINT fk_episode FOREIGN KEY (catalogo_id) REFERENCES catalogo(id)

)ENGINE = InnoDb;

CREATE INDEX idx_acapite ON episode(acapite);

CREATE table IF NOT EXISTS orden(

    id              int(255),
    orden           int(255),

    CONSTRAINT fk_orden_id FOREIGN KEY (id) REFERENCES episode(catalogo_id),
    CONSTRAINT fk_orden FOREIGN KEY (orden) REFERENCES episode(acapite)

)ENGINE = InnoDB;


