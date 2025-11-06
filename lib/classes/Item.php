<?php
// Minimal Item (property) model for CRUD operations
class Item
{
    public static function getList($limit = 50, $offset = 0, $onlyPublished = false)
    {
        $sql = "SELECT * FROM items";
        if ($onlyPublished) {
            $sql .= " WHERE published = 1";
        }
        $sql .= " ORDER BY created_at DESC LIMIT :off, :lim";
        return Db::query($sql, ['off' => (int)$offset, 'lim' => (int)$limit]);
    }

    public static function countAll($onlyPublished = false)
    {
        $sql = "SELECT COUNT(*) FROM items";
        if ($onlyPublished) {
            $sql .= " WHERE published = 1";
        }
        return (int) Db::query_one($sql);
    }

    public static function getById($id)
    {
        return Db::query_row("SELECT * FROM items WHERE id = :id LIMIT 1", ['id' => (int)$id]);
    }

    public static function create(array $data)
    {
        $fields = [];
        $placeholders = [];
        $params = [];
        foreach ($data as $k => $v) {
            $fields[] = "`$k`";
            $placeholders[] = ":" . $k;
            $params[$k] = $v;
        }
        $sql = "INSERT INTO items (" . implode(',', $fields) . ") VALUES (" . implode(',', $placeholders) . ")";
        Db::exec($sql, $params);
        return Db::insert_id();
    }

    public static function update($id, array $data)
    {
        $sets = [];
        $params = [];
        foreach ($data as $k => $v) {
            $sets[] = "`$k` = :" . $k;
            $params[$k] = $v;
        }
        $params['id'] = (int)$id;
        $sql = "UPDATE items SET " . implode(',', $sets) . " WHERE id = :id";
        return Db::exec($sql, $params);
    }

    public static function delete($id)
    {
        return Db::exec("DELETE FROM items WHERE id = :id", ['id' => (int)$id]);
    }
}
?>