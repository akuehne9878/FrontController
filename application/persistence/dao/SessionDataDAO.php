<?php

namespace FrontController\application\persistence\dao;

use FrontController\application\persistence\dao\AbstractDAO;
use FrontController\application\business\domain\objects\SessionData;
use \PDO;


/**
 *
 */
class SessionDataDAO extends AbstractDAO {

    /**
     * Constructor with optional connection
     */
    public function __construct(PDO $connection = null) {
        parent::__construct(get_class(new SessionData()), $connection);
    }

    /**
     * Save entity
     */
    public function save(SessionData $sessionData) {
        // define the statement
        $sql = '
            INSERT INTO session 
                (username, session) 
            VALUES 
                (:username, :session)
        ';

        // set the bind parameters
        $bindparams[':username'] = $sessionData->getUsername();
        $bindparams[':session'] = $sessionData->getSession();

        // execute
        return parent::save($sql, $bindparams);
    }

    /**
     * Update entity
     */
    public function update(SessionData $sessionData) {
        // define the statement
        $sql = '
            UPDATE session
            SET session = :session                
            WHERE username = :username
            AND session = :session
        ';

        // set the bind parameters
        $bindparams[':username'] = $sessionData->getUsername();
        $bindparams[':session'] = $sessionData->getSession();

        // execute
        return parent::update($sql, $bindparams);
    }

    /**
     * Delete entity
     */
    public function delete($username, $session) {
        // define the statement
        $sql = '
            DELETE FROM session
            WHERE username = :username
            AND session = :session
        ';

        // set the bind parameters
        $bindparams[':username'] = $username;
        $bindparams[':session'] = $session;
        
        // execute
        return parent::delete($sql, $bindparams);
    }

    /**
     * Search
     */
    public function search(SessionData $sessionData) {
        // define the statement
        $sql = '
            SELECT * 
            FROM session 
            WHERE 1=1
        ';

        $bindparams = array();
        if ($sessionData->getUsername()) {
            $sql .= '	AND username = :username';
            $bindparams[':username'] = $sessionData->getUsername();
        }

        if ($sessionData->getSession()) {
            $sql .= '	AND session = :session';
            $bindparams[':session'] = $sessionData->getSession();
        }

        // execute
        return parent::executeReadStatement($sql, $bindparams);
    }

}
