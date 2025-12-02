// recibes $_POST['perm'] = array of perm_id
$role_id = $_POST['role_id'];
$selected = $_POST['perm'] ?? [];
// borrar existentes
runQuery($conn, "DELETE FROM role_permissions WHERE role_id = ?", [$role_id]);
// insertar seleccionados
foreach ($selected as $pid) {
    runQuery($conn, "INSERT INTO role_permissions (role_id, perm_id) VALUES (?, ?)", [$role_id, $pid]);
}
