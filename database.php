<?php

class database
{

  private $db_host = "localhost";
  private $db_user = "root";
  private $db_pass = "user@123";
  private $db_name = "oops";

  private $mysqli = '';
  private $result = array();
  private $conn = false;


  public function __construct()
  {
    if (!$this->conn) {

      $this->mysqli = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);

      if ($this->mysqli->connect_error) {
        array_push($this->result, $this->mysqli->connect_error);
        return false;
      }
    } else {
      return true;
    }
  }
  public function insert($table, $params = array())
  {

    if ($this->tableExist($table)) {

      $table_columns = implode(' , ', array_keys($params));
      $table_values = implode("' , '", $params);

      $sql = "INSERT INTO $table ($table_columns) VALUES ('$table_values')";

      if ($this->mysqli->query($sql)) {

        array_push($this->result, $this->mysqli->insert_id);
        return true;
      } else {
        array_push($this->result, $this->mysqli->error);
        return false;
      }
    } else {
      return false;
    }
  }

  public function update($table, $params = array(), $where = null)
  {
    if ($this->tableExist($table)) {

      $args = array();

      foreach ($params as $key => $value) {
        $args[] = "$key = '$value'";
      }
      print_r($args);


      $sql = "UPDATE $table SET " . implode(', ', $args);
      if ($where != null) {
        $sql .= " WHERE $where";
      }
      if ($this->mysqli->query($sql)) {

        array_push($this->result, $this->mysqli->affected_rows);
        return true;
      } else {
        array_push($this->result, $this->mysqli->error);
        return false;
      }
    } else {
      return false;
    }
  }
  public function delete($table, $where = null)
  {
    if ($this->tableExist($table)) {

      $sql = "DELETE FROM $table";

      if ($where != null) {

        echo  $sql .= " WHERE $where";
      }
      if ($this->mysqli->query($sql)) {

        array_push($this->result, $this->mysqli->affected_rows);
        return true;
      } else {
        array_push($this->result, $this->mysqli->error);
        return false;
      }
    } else {
      return true;
    }
  }
  public function select($table, $cloumns = null, $where = null, $join = null, $orderBy = null, $limit = null)
  {
    if ($this->tableExist($table)) {

      $sql = "SELECT $cloumns FROM $table";

      if ($where) {
        $sql .= " WHERE $where";
      } elseif ($join) {
        $sql .= " JOIN $join";
      } elseif ($orderBy) {
        $sql .= " ORDER BY $orderBy";
      } else if ($limit) {
        $sql .= " LIMIT $limit";
      }

      $this->sql($sql);
    } else {
      array_push($this->result, "$table does not exist.");
      return false;
    }
  }

  private function sql($sql)
  {

    echo $sql;
    $query = $this->mysqli->query($sql);
    if ($query) {
      while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
        array_push($this->result, $row);
      }
      return true;
    } else {
      array_push($this->result, $this->mysqli->error);
      return false;
    }
  }

  private function tableExist($table)
  {
    $sql = "SHOW TABLES FROM $this->db_name LIKE '$table'";
    $tableInDB = $this->mysqli->query($sql);
    if ($tableInDB) {
      if ($tableInDB->num_rows == 1) {
        return true;
      } else {
        array_push($this->result, $table . "does not exist in database");
        return false;
      }
    }
  }

  public function getResult()
  {
    $val = $this->result;
    $this->result = array();
    return $val;;
  }

  //for close connection

  public function __destruct()
  {
    if ($this->conn) {

      if ($this->mysqli->close()) {
        $this->conn = false;
        return true;
      }
    } else {
      return false;
    }
  }
}
