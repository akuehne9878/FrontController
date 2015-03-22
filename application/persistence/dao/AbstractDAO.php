<?php

namespace FrontController\application\persistence\dao;

use \PDO;
use Commons\utils\Logger;
use FrontController\application\business\utils\FrontControllerUtils;

abstract class AbstractDAO {

    private $connection;
    private $classname;

    /**
     *
     */
    public function __construct($classname, PDO $connection = null) {
        $this->classname = $classname;
        $this->connection = $connection;
        if ($this->connection === null) {
            $this->connection = AbstractDAO::getConnection();
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }

    /**
     * returns a connection to the db
     */
    public static function getConnection() {
        
        $dsn = null;
        $user = null;
        $password = null;

        if (FrontControllerUtils::isDevelopmentEnvironment()) {
            $dsn = 'mysql:host=localhost;dbname=raumklang;charset=utf8';
            $user = 'root';
            $password = '';
        } else {
            $dsn = 'mysql:host=localhost;dbname=amadeus88;charset=utf8';
            $user = 'amadeus88';
            $password = 'layecu5o';
        }

        try {
            $dbh = new PDO($dsn, $user, $password, array(PDO::ATTR_PERSISTENT => true));
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
        return $dbh;
    }

    /**
     * READ ACCESS: returns list of objects
     */
    private function readObjects($sql, $bindparams, $classname = null) {
        Logger::log("++++++++");
        Logger::log("EXECUTING SQL: " . $sql);
        $stmt = $this->connection->prepare($sql);
        Logger::log("++++++++");
        foreach ($bindparams as $key => &$value) {
            $stmt->bindParam($key, $value);
            Logger::log("BIND PARAM: " . $key . "=" . $value);
        }

        $stmt->execute();

        if ($classname) {
            $stmt->setFetchMode(PDO::FETCH_CLASS, $classname);
        } else {
            $stmt->setFetchMode(PDO::FETCH_CLASS, $this->classname);
        }

        $ret = $stmt->fetchAll();

        Logger::log("++++++++");
        Logger::log("RESULT: " . print_r($ret, true));
        Logger::log("++++++++");

        return $ret;
    }

    /**
     * READ ACCESS: returns one object
     */
    private function readFirstObject($sql, $bindparams, $classname = null) {
        Logger::log("++++++++");
        Logger::log("EXECUTING SQL: " . $sql);
        $stmt = $this->connection->prepare($sql);
        Logger::log("++++++++");
        foreach ($bindparams as $key => &$value) {
            $stmt->bindParam($key, $value);
            Logger::log("BIND PARAM: " . $key . "=" . $value);
        }

        $stmt->execute();
        if ($classname) {
            $stmt->setFetchMode(PDO::FETCH_CLASS, $classname);
        } else {
            $stmt->setFetchMode(PDO::FETCH_CLASS, $this->classname);
        }

        $ret = $stmt->fetch();
        Logger::log("++++++++");
        Logger::log("RESULT: " . print_r($ret, true));
        Logger::log("++++++++");
        return $ret;
    }

    /**
     * 	WRITE ACCESS
     */
    private function writeObject($sql, $bindparams) {
        Logger::log("++++++++");
        Logger::log("EXECUTING SQL: " . $sql);
        Logger::log("++++++++");
        $ret;
        try {

            $this->connection->beginTransaction();
            $stmt = $this->connection->prepare($sql);

            foreach ($bindparams as $key => &$value) {
                $stmt->bindParam($key, $value);
                Logger::log("BIND PARAM: " . $key . "=" . $value);
            }
            $ret = $stmt->execute();
            $this->connection->commit();
            Logger::log("++++++++");
            Logger::log("RESULT: " . print_r($ret, true));
            Logger::log("++++++++");
        } catch (Exception $e) {
            $this->connection->rollBack();
        }
        return $ret;
    }

    /**
     *
     */
    protected function load($sql, $bindparams) {
        return $this->readFirstObject($sql, $bindparams);
    }

    /**
     *
     */
    protected function save($sql, $bindparams) {
        return $this->writeObject($sql, $bindparams);
    }

    /**
     *
     */
    protected function update($sql, $bindparams) {
        return $this->writeObject($sql, $bindparams);
    }

    /**
     *
     */
    protected function delete($sql, $bindparams) {
        return $this->writeObject($sql, $bindparams);
    }

    protected function executeReadStatement($sql, $bindparams, $classname = null) {
        return $this->readObjects($sql, $bindparams, $classname);
    }

    protected function executeWriteStatement($sql, $bindparams) {
        return $this->writeObject($sql, $bindparams);
    }

}

?>