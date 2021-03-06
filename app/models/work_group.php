<?php

class WorkGroup extends BaseModel {

    public $id, $name, $description;

    public function __construct($attributes = null) {
        parent::__construct($attributes);
    }

    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Work_group WHERE id = :id');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $group = new WorkGroup(array(
                'id' => $row['id'],
                'name' => $row['name'],
                'description' => $row['description']
            ));
            return $group;
        }
        return null;
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Work_group');
        $query->execute();
        $rows = $query->fetchAll();
        $groups = array();

        foreach ($rows as $row) {
            $groups[] = new WorkGroup(array(
                'id' => $row['id'],
                'name' => $row['name'],
                'description' => $row['description']
            ));
        }
//        Kint::trace();
//        Kint::dump();
        return $groups;
    }

}
