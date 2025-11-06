<?php
class Admin_User
{
    protected $id = null;
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['admin']['id'])) {
            $this->id = (int) $_SESSION['admin']['id'];
        }
    }

    public function is_logged()
    {
        if ($this->id) {
            $row = Db::query_row('SELECT id, username FROM admin_users WHERE id = '.(int)$this->id.' LIMIT 1');
            return (bool)$row;
        }
        return false;
    }

    public function login($username, $password)
    {
        $row = Db::query_row('SELECT id, username, password FROM admin_users WHERE username = :u LIMIT 1', ['u' => $username]);
        if (! $row) {
            return false;
        }
        // password stored with password_hash
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin'] = [
                'id' => (int)$row['id'],
                'username' => $row['username'],
            ];
            $this->id = (int)$row['id'];
            return true;
        }
        return false;
    }

    public function logout()
    {
        unset($_SESSION['admin']);
        $this->id = null;
    }

    // helper: return admin data
    public function get()
    {
        if ($this->id) {
            return Db::query_row('SELECT id, username, name, email FROM admin_users WHERE id = '.(int)$this->id.' LIMIT 1');
        }
        return null;
    }
}
?>