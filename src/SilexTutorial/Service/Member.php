<?php

namespace SilexTutorial\Service;

/**
 * Member class
 */
class Member
{

    const STRETCHCOUNT = 1000;

    protected $db;

    protected $data = array();

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * register 
     * 
     * @param array $data 
     * @return void
     */
    public function register(array $data)
    {
        $result = false;

        $this->db->beginTransaction();

        try
        {
            $sql = "INSERT INTO member SET
                email = :email,
                password = 'dummy',
                created_at = now()";
            $stmt = $this->db->prepare($sql);

            $stmt->bindParam(':email', $data['email']);
            $stmt->execute();

            $id = $this->db->lastInsertId();

            $sql = "UPDATE member SET
                password = :password,
                updated_at = now()
                WHERE id = :id";

            $stmt = $this->db->prepare($sql);
            $password = $this->passwordHash($id, $data['password']);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam('id', $id);
            
            $result = $stmt->execute();
        }
        catch(Exception $e)
        {
            $this->db->rollback();
            throw $e;
        }

        $this->db->commit();

        return $result;
    }

    /**
     * getSalt 
     * 
     * @param string $id 
     * @return void
     */
    protected function getSalt($id)
    {
        return md5($id);
    }

    /**
     * passwordHash 
     * 
     * @param int $id 
     * @param string $password 
     * @return void
     */
    protected function passwordHash($id, $password)
    {
        $salt = $this->getSalt($id);
        $hash = '';
        for($i = 0; $i < self::STRETCHCOUNT; $i++)
        {
            $hash = hash('sha256', $hash . $password . $salt);
        }

        return $hash;
    }

}
