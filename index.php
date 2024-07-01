<?php

include 'database.php';

$obj = new database();

$obj->insert('students', ['name' => 'Rajesh', 'age' => 25, 'city' => 'Mumbai']);

echo "Insert Result is :";

print_r($obj->getResult());

$obj->update('students', ['name' => 'xyz', 'age' => 30, 'city' => 'Dehlit'], 'id="7"');

echo "Update Result is :";

print_r($obj->getResult());

$obj->delete('students', 'id="8"');

echo "Delete Result is :";

print_r($obj->getResult());

$obj->select('students', "*", null, null, null, 2);

echo " Fetch Result is : ";

echo "<pre>";
print_r($obj->getResult());
echo "</pre>";
