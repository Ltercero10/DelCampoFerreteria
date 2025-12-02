<?php
namespace Dao;

class AuthDao {
    public static function getUserRoles($usercod) {
        $sql = "SELECT r.role_id, r.role_name FROM roles r
                JOIN user_roles ur ON ur.role_id = r.role_id
                WHERE ur.usercod = ?";
        return \Dao\Table::obtenerRegistros($sql, [$usercod]); // ajusta según Table:: existe
    }

    public static function getUserPermissions($usercod) {
        $sql = "SELECT p.perm_key FROM permissions p
                JOIN role_permissions rp ON rp.perm_id = p.perm_id
                JOIN user_roles ur ON ur.role_id = rp.role_id
                WHERE ur.usercod = ?
                GROUP BY p.perm_key";
        return \Dao\Table::obtenerRegistros($sql, [$usercod]); // devuelve array de rows
    }

    public static function userHasPermission($usercod, $perm_key) {
        $sql = "SELECT 1 FROM permissions p
                JOIN role_permissions rp ON rp.perm_id = p.perm_id
                JOIN user_roles ur ON ur.role_id = rp.role_id
                WHERE ur.usercod = ? AND p.perm_key = ? LIMIT 1";
        $r = \Dao\Table::obtenerUnRegistro($sql, [$usercod, $perm_key]);
        return !empty($r);
    }
}
