






select * from proyectos_productivos;

select * from encuestadores;

select * from afectaciones;

select * from familiares;

select * from caracterizaciones;



select * from predio;

select * from control_actividades;


select * from gestion_agropecuaria;


select * from inventario_pecuario;


select * from maquinaria;

select * from producciones;

select * from descripcions;


select * from viviendas;

select * from users;

select * from encuestas;

CREATE TABLE roles (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);


CREATE TABLE role_user (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL,
    role_id INTEGER NOT NULL,
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

ALTER TABLE role_user
ADD CONSTRAINT fk_user
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;

ALTER TABLE role_user
ADD CONSTRAINT fk_role
FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE;


SELECT * FROM roles;
SELECT * FROM users;

