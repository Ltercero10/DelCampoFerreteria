-- =================================================================
-- SCRIPT DE CORRECCIÓN DE PERMISOS Y ROLES (FINAL)
-- Ejecutar esto para arreglar el menú y los accesos de Productos
-- =================================================================

-- 1. Asegurar que existen los Roles
INSERT IGNORE INTO roles (rolescod, rolesdsc, rolesest) VALUES ('ADMIN', 'Administrador', 'ACT');
INSERT IGNORE INTO roles (rolescod, rolesdsc, rolesest) VALUES ('CLIENT', 'Cliente', 'ACT');

-- 2. Registrar las PÁGINAS (Controladores) como Funciones
INSERT IGNORE INTO funciones (fncod, fndsc, fnest, fntyp) VALUES ('Controllers\\Mantenimientos\\Productos\\ProductosList', 'Page: List Products', 'ACT', 'CTR');
INSERT IGNORE INTO funciones (fncod, fndsc, fnest, fntyp) VALUES ('Controllers\\Mantenimientos\\Productos\\ProductosForm', 'Page: Edit Product', 'ACT', 'CTR');

-- 3. Registrar los BOTONES DEL MENÚ (IDs deben coincidir con nav.config.json)
INSERT IGNORE INTO funciones (fncod, fndsc, fnest, fntyp) VALUES ('Menu_Catalogo', 'Menu: Mantenimiento Productos', 'ACT', 'MNU');
INSERT IGNORE INTO funciones (fncod, fndsc, fnest, fntyp) VALUES ('Menu_Historial', 'Menu: Ver Historial', 'ACT', 'MNU');
INSERT IGNORE INTO funciones (fncod, fndsc, fnest, fntyp) VALUES ('Menu_PaymentCheckout', 'Menu: Ver Carrito', 'ACT', 'MNU');

-- =================================================================
-- PERMISOS DE ADMINISTRADOR (Acceso Total)
-- =================================================================
-- Acceso a las páginas
INSERT IGNORE INTO funciones_roles (rolescod, fncod, fnrolest, fnexp) VALUES ('ADMIN', 'Controllers\\Mantenimientos\\Productos\\ProductosList', 'ACT', '2030-01-01');
INSERT IGNORE INTO funciones_roles (rolescod, fncod, fnrolest, fnexp) VALUES ('ADMIN', 'Controllers\\Mantenimientos\\Productos\\ProductosForm', 'ACT', '2030-01-01');
-- Acceso a los menús
INSERT IGNORE INTO funciones_roles (rolescod, fncod, fnrolest, fnexp) VALUES ('ADMIN', 'Menu_Catalogo', 'ACT', '2030-01-01');
INSERT IGNORE INTO funciones_roles (rolescod, fncod, fnrolest, fnexp) VALUES ('ADMIN', 'Menu_Historial', 'ACT', '2030-01-01');
INSERT IGNORE INTO funciones_roles (rolescod, fncod, fnrolest, fnexp) VALUES ('ADMIN', 'Menu_PaymentCheckout', 'ACT', '2030-01-01');

-- =================================================================
-- PERMISOS DE CLIENTE (Acceso Restringido)
-- =================================================================
-- Acceso a las páginas (Necesario para entrar, pero la vista oculta los botones de editar)
INSERT IGNORE INTO funciones_roles (rolescod, fncod, fnrolest, fnexp) VALUES ('CLIENT', 'Controllers\\Mantenimientos\\Productos\\ProductosList', 'ACT', '2030-01-01');
INSERT IGNORE INTO funciones_roles (rolescod, fncod, fnrolest, fnexp) VALUES ('CLIENT', 'Controllers\\Mantenimientos\\Productos\\ProductosForm', 'ACT', '2030-01-01');

-- Acceso a los menús (SOLO Historial y Carrito, NO Catálogo de Mantenimiento)
INSERT IGNORE INTO funciones_roles (rolescod, fncod, fnrolest, fnexp) VALUES ('CLIENT', 'Menu_Historial', 'ACT', '2030-01-01');
INSERT IGNORE INTO funciones_roles (rolescod, fncod, fnrolest, fnexp) VALUES ('CLIENT', 'Menu_PaymentCheckout', 'ACT', '2030-01-01');

-- LIMPIEZA: Asegurar que el Cliente NO tenga el menú de mantenimiento
DELETE FROM funciones_roles WHERE rolescod = 'CLIENT' AND fncod = 'Menu_Catalogo';