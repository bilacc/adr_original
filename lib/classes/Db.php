<?php
// Static façade used by legacy code (Db::query, Db::query_row, Db::query_one, Db::clean)
class Db
{
    protected static function db()
    {
        if (isset($GLOBALS['db_instance']) && $GLOBALS['db_instance'] instanceof Database) {
            return $GLOBALS['db_instance'];
        }
        // fallback: create a Database instance
        $db = new Database();
        return $GLOBALS['db_instance'];
    }

    public static function query($sql, $params = [])
    {
        $_SESSION['sql_log']['last_sql'] = $sql;
        return self::db()->queryAll($sql, $params);
    }

    public static function query_row($sql, $params = [])
    {
        $_SESSION['sql_log']['last_sql'] = $sql;
        return self::db()->queryRow($sql, $params);
    }

    public static function query_one($sql, $params = [])
    {
        $_SESSION['sql_log']['last_sql'] = $sql;
        return self::db()->queryOne($sql, $params);
    }

    public static function exec($sql, $params = [])
    {
        $_SESSION['sql_log']['last_sql'] = $sql;
        return self::db()->exec($sql, $params);
    }

    public static function insert_id()
    {
        return self::db()->lastInsertId();
    }

    // Very conservative clean: allow only alnum and underscore and dot
    public static function clean($str)
    {
        return preg_replace('/[^A-Za-z0-9_\.]/', '', $str);
    }
}
?>