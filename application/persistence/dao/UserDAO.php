<?php

namespace FrontController\application\persistence\dao;

use FrontController\application\persistence\dao\AbstractDAO;
use FrontController\application\business\domain\objects\User;
use \PDO;

/**
 *
 */
class UserDAO extends AbstractDAO {

    /**
     * Constructor with optional connection
     */
    public function __construct(PDO $connection = null) {
        parent::__construct(get_class(new User()), $connection);
    }

    /**
     * Load entity by primary key
     */
    public function load($username) {
        // define the statement
        $sql = '
            SELECT username, email, firstname, lastname 
            FROM user 
            WHERE email = :username
        ';

        // set the bind parameters
        $bindparams[':username'] = $username;

        // execute
        return parent::load($sql, $bindparams);
    }

    /**
     * Save entity
     */
    public function save(User $user) {
        // define the statement
        $sql = '
            INSERT INTO user 
                (username, email, password, firstname, lastname) 
            VALUES 
                (:username, :email, :password, :firstname, :lastname)
        ';

        // set the bind parameters
        $bindparams[':username'] = $user->getUsername();
        $bindparams[':email'] = $user->getEmail();
        $bindparams[':firstname'] = $user->getFirstname();
        $bindparams[':lastname'] = $user->getLastname();
        $bindparams[':password'] = $user->getPassword();

        // execute
        return parent::save($sql, $bindparams);
    }

    /**
     * Update entity
     */
    public function update(User $user) {
        // define the statement
        $sql = '
            UPDATE user
            SET password = :password,
                firstname = :firstname,
                lastname = :lastname,
                email = :email
            WHERE username = :username
        ';

        // set the bind parameters
        $bindparams[':username'] = $user->getUsername();
        $bindparams[':email'] = $user->getEmail();
        $bindparams[':firstname'] = $user->getFirstName();
        $bindparams[':lastname'] = $user->getLastName();
        $bindparams[':password'] = $user->getPassword();

        // execute
        return parent::update($sql, $bindparams);
    }

    /**
     * Delete entity
     */
    public function delete($username) {
        // define the statement
        $sql = '
            DELETE FROM user
            WHERE username = :username
        ';

        // set the bind parameters
        $bindparams[':username'] = $username;

        // execute
        return parent::delete($sql, $bindparams);
    }

    /**
     * Check for login
     */
    public function checkForLogin($email, $password) {
        // define the statement
        $sql = '
            SELECT username, email, firstname, lastname 
            FROM user 
            WHERE email = :email
            AND password = :password
        ';

        // set the bind parameters
        $bindparams[':email'] = $email;
        $bindparams[':password'] = $password;

        // execute
        return parent::load($sql, $bindparams);
    }

    /**
     * 
     */
    public function checkForSession($session) {
        // define the statement
        $sql = '		
            SELECT DISTINCT usr.username, usr.email, usr.firstname, usr.lastname
            FROM session session_user
            INNER JOIN user usr ON usr.username = session_user.username
            WHERE session_user.session = :session
        ';

        // set the bind parameters
        $bindparams[':session'] = $session;

        // execute
        return parent::load($sql, $bindparams);
    }

    /**
     *
     */
    public function searchPageForDataGrid(User $user, $pageIndex, $pageSize, $classname) {
        // define the statement
        $sql = '
            SELECT username, email, firstname, lastname 
            FROM user 
            WHERE 1=1
        ';

        $bindparams = array();
        if ($user->getFirstname()) {
            $sql .= '	AND firstname = :firstname';
            $bindparams[':firstname'] = $user->getFirstname();
        }
        if ($user->getLastname()) {
            $sql .= '	AND lastname = :lastname';
            $bindparams[':lastname'] = $user->getLastname();
        }
        if ($user->getEmail()) {
            $sql .= '	AND email = :email';
            $bindparams[':email'] = $user->getEmail();
        }
        if ($user->getUsername()) {
            $sql .= '	AND username = :username';
            $bindparams[':username'] = $user->getUsername();
        }


        $paginatedSql = $this->modifyStatementForPagination($sql, $pageIndex, $pageSize);

        // execute
        return parent::executeReadStatement($paginatedSql, $bindparams, $classname);
    }

    private function modifyStatementForPagination($sql, $pageIndex, $pageSize) {
        $offset = $pageIndex * $pageSize;

        $temp = "SELECT * FROM ( ";
        $temp .= $sql;
        $temp .= ") page LIMIT " . $offset . ", " . $pageSize;

        return $temp;
    }

}
