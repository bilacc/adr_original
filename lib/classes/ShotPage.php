<?php
// Minimal page object used by index.php
class ShotPage
{
    public $data = [];

    public function __construct()
    {
        // Provide default header/content/footer includes.
        // Project originally used include/<header|content|footer>.php — create these if missing.
        $this->data['header'] = 'header';
        $this->data['content'] = 'home';
        $this->data['footer'] = 'footer';
    }
}