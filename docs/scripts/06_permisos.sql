INSERT IGNORE INTO roles (rolescod, rolesdsc, rolesest) VALUES ('ADMIN', 'Administrador', 'ACT');
INSERT IGNORE INTO roles (rolescod, rolesdsc, rolesest) VALUES ('CLIENT', 'Publico', 'ACT');


INSERT IGNORE INTO funciones (fncod, fndsc, fnest, fntyp) VALUES ('Controllers\\Mantenimientos\\Productos\\ProductosList', 'Page: List Products', 'ACT', 'CTR');
INSERT IGNORE INTO funciones (fncod, fndsc, fnest, fntyp) VALUES ('Controllers\\Mantenimientos\\Productos\\ProductosForm', 'Page: Edit Product', 'ACT', 'CTR');


INSERT IGNORE INTO funciones (fncod, fndsc, fnest, fntyp) VALUES ('Menu_Catalogo', 'Menu: Mantenimiento Productos', 'ACT', 'MNU');
INSERT IGNORE INTO funciones (fncod, fndsc, fnest, fntyp) VALUES ('Menu_Historial', 'Menu: Ver Historial', 'ACT', 'MNU');
INSERT IGNORE INTO funciones (fncod, fndsc, fnest, fntyp) VALUES ('Menu_PaymentCheckout', 'Menu: Ver Carrito', 'ACT', 'MNU');

INSERT IGNORE INTO funciones_roles (rolescod, fncod, fnrolest, fnexp) VALUES ('ADMIN', 'Controllers\\Mantenimientos\\Productos\\ProductosList', 'ACT', '2030-01-01');
INSERT IGNORE INTO funciones_roles (rolescod, fncod, fnrolest, fnexp) VALUES ('ADMIN', 'Controllers\\Mantenimientos\\Productos\\ProductosForm', 'ACT', '2030-01-01');

INSERT IGNORE INTO funciones_roles (rolescod, fncod, fnrolest, fnexp) VALUES ('ADMIN', 'Menu_Catalogo', 'ACT', '2030-01-01');
INSERT IGNORE INTO funciones_roles (rolescod, fncod, fnrolest, fnexp) VALUES ('ADMIN', 'Menu_Historial', 'ACT', '2030-01-01');
INSERT IGNORE INTO funciones_roles (rolescod, fncod, fnrolest, fnexp) VALUES ('ADMIN', 'Menu_PaymentCheckout', 'ACT', '2030-01-01');


INSERT IGNORE INTO funciones_roles (rolescod, fncod, fnrolest, fnexp) VALUES ('CLIENT', 'Controllers\\Mantenimientos\\Productos\\ProductosList', 'ACT', '2030-01-01');
INSERT IGNORE INTO funciones_roles (rolescod, fncod, fnrolest, fnexp) VALUES ('CLIENT', 'Controllers\\Mantenimientos\\Productos\\ProductosForm', 'ACT', '2030-01-01');


INSERT IGNORE INTO funciones_roles (rolescod, fncod, fnrolest, fnexp) VALUES ('CLIENT', 'Menu_Historial', 'ACT', '2030-01-01');
INSERT IGNORE INTO funciones_roles (rolescod, fncod, fnrolest, fnexp) VALUES ('CLIENT', 'Menu_PaymentCheckout', 'ACT', '2030-01-01');

DELETE FROM funciones_roles WHERE rolescod = 'CLIENT' AND fncod = 'Menu_Catalogo';

