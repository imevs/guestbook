<?php
class DB
{
    /**
     * @var PDO
     */
    private $connection;

    public static function getInstance()
    {
        static $instance;
        return $instance ? $instance : $instance = new DB();
    }

    public function __construct()
    {
        $db_filename = APP_DIR .  '/data/data.sqlite';
        $this->connection = new PDO('sqlite:'. $db_filename);
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function getMessages()
    {
        $res = $this->getConnection()->query('select * from messages', PDO::FETCH_CLASS, 'Message');
        $collection  = new MessageCollection();
        foreach($res as $item) {
            $collection->add($item);
        }
        return $collection;
    }

    public function getUsers()
    {
    }

}