<?php
// docs/scripts/seed_roles_users.php
require_once __DIR__ . '/../../vendor/autoload.php'; // ajusta ruta si es necesario
use Dao\Dao;

// Cambia si tu Dao requiere otro namespace o constructor
$conn = \Dao\Dao::getConn(); // usa la conexión del framework

function runQuery($conn, $sql, $params = []) {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

// 1) Roles
$roles = [
    ['admin', 'Administrador con todos los permisos'],
    ['gerente', 'Gerente, permisos de inventario y ventas'],
    ['vendedor', 'Vendedor, gestiona ventas'],
    ['auditor', 'Permisos solo lectura'],
    ['cliente', 'Cliente, acceso mínimo']
];
foreach ($roles as $r) {
    runQuery($conn, "INSERT IGNORE INTO roles (role_name, role_desc) VALUES (?, ?)", $r);
}

// 2) Permisos (ejemplos)
$perms = [
    ['users.create','Crear usuarios'],
    ['users.edit','Editar usuarios'],
    ['users.delete','Eliminar usuarios'],
    ['products.create','Crear productos'],
    ['products.edit','Editar productos'],
    ['products.view','Ver productos'],
    ['orders.create','Crear pedidos'],
    ['orders.view','Ver pedidos'],
    ['reports.view','Generar reportes']
];
foreach ($perms as $p) {
    runQuery($conn, "INSERT IGNORE INTO permissions (perm_key, perm_desc) VALUES (?, ?)", $p);
}

// 3) Asignar permisos a roles (ejemplo simple)
$rolePermMap = [
    'admin' => ['users.create','users.edit','users.delete','products.create','products.edit','products.view','orders.create','orders.view','reports.view'],
    'gerente' => ['products.create','products.edit','products.view','orders.view','reports.view'],
    'vendedor' => ['products.view','orders.create','orders.view'],
    'auditor' => ['products.view','orders.view','reports.view'],
    'cliente' => ['products.view','orders.create']
];

foreach ($rolePermMap as $roleName => $permKeys) {
    $r = runQuery($conn, "SELECT role_id FROM roles WHERE role_name = ?", [$roleName])->fetch(\PDO::FETCH_ASSOC);
    if (!$r) continue;
    $role_id = $r['role_id'];
    foreach ($permKeys as $pk) {
        $p = runQuery($conn, "SELECT perm_id FROM permissions WHERE perm_key = ?", [$pk])->fetch(\PDO::FETCH_ASSOC);
        if (!$p) continue;
        $perm_id = $p['perm_id'];
        runQuery($conn, "INSERT IGNORE INTO role_permissions (role_id, perm_id) VALUES (?, ?)", [$role_id, $perm_id]);
    }
}

// 4) Crear 5 usuarios de ejemplo (si tu tabla usuario tiene campos diferentes, ajusta)
$users = [
    ['admin@demo.com','Administrador Demo','Password123!','ACT'],
    ['gerente@demo.com','Gerente Demo','Password123!','ACT'],
    ['vendedor@demo.com','Vendedor Demo','Password123!','ACT'],
    ['auditor@demo.com','Auditor Demo','Password123!','ACT'],
    ['cliente@demo.com','Cliente Demo','Password123!','ACT'],
];

foreach ($users as $u) {
    list($email,$name,$plain,$est) = $u;
    $hash = password_hash($plain, PASSWORD_DEFAULT);
    // Ajusta los campos según la tabla usuario real (useremail, username, userpswd, userest, userfching)
    runQuery($conn, "INSERT IGNORE INTO usuario (useremail, username, userpswd, userfching, userest) VALUES (?, ?, ?, NOW(), ?)", [$email,$name,$hash,$est]);
}

// 5) Asignar roles a los usuarios (por orden)
$roleNames = ['admin','gerente','vendedor','auditor','cliente'];
foreach ($roleNames as $i => $rname) {
    $uemail = $users[$i][0];
    $user = runQuery($conn, "SELECT usercod FROM usuario WHERE useremail = ?", [$uemail])->fetch(\PDO::FETCH_ASSOC);
    $role = runQuery($conn, "SELECT role_id FROM roles WHERE role_name = ?", [$rname])->fetch(\PDO::FETCH_ASSOC);
    if ($user && $role) {
        runQuery($conn, "INSERT IGNORE INTO user_roles (usercod, role_id) VALUES (?, ?)", [$user['usercod'], $role['role_id']]);
    }
}

echo "Seed completado.\n";
