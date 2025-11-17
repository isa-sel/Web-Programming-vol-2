<?php

require_once __DIR__ . '/../dao/BaseDao.php';

class BaseService {

    protected $dao;

    public function __construct($dao)
    {
        $this->dao = $dao;
    }

    // GENERIČNE METODE ZA SERVISE (snake_case izvana):

    public function get_all()
    {
        // Dao vjerovatno ima getAll()
        return $this->dao->getAll();
    }

    public function get_by_id($id)
    {
        // Dao vjerovatno ima getById($id)
        return $this->dao->getById($id);
    }

    public function insert($entity)
    {
        // Već smo utvrdili da treba insert(), ne add()
        return $this->dao->insert($entity);
    }

    public function update($entity, $id, $id_column = "id")
    {
        return $this->dao->update($entity, $id, $id_column);
    }

    public function delete($id)
    {
        return $this->dao->delete($id);
    }
}
