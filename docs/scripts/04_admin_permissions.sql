
INSERT IGNORE INTO funciones (fncod, fndsc, fnest, fntyp) VALUES ('Controllers\\Admin\\Usuarios', 'Pagina: Ver Usuarios', 'ACT', 'CTR');
INSERT IGNORE INTO funciones (fncod, fndsc, fnest, fntyp) VALUES ('Controllers\\Admin\\UsuarioRoles', 'Pagina: Asignar Roles', 'ACT', 'CTR');
INSERT IGNORE INTO funciones (fncod, fndsc, fnest, fntyp) VALUES ('Menu_Usuarios', 'Menu: Gestion Usuarios', 'ACT', 'MNU');


INSERT IGNORE INTO funciones_roles (rolescod, fncod, fnrolest, fnexp) VALUES ('ADMIN', 'Controllers\\Admin\\Usuarios', 'ACT', '2030-01-01');
INSERT IGNORE INTO funciones_roles (rolescod, fncod, fnrolest, fnexp) VALUES ('ADMIN', 'Controllers\\Admin\\UsuarioRoles', 'ACT', '2030-01-01');
INSERT IGNORE INTO funciones_roles (rolescod, fncod, fnrolest, fnexp) VALUES ('ADMIN', 'Menu_Usuarios', 'ACT', '2030-01-01');