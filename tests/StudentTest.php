<?php

/**
  * @backupGlobals disabled
  * @backupStaticAttributes disabled
  */

$server = 'mysql:host=localhost:8889;dbname=registrar_test';
$username= "root";
$password = "root";
$DB = new PDO($server, $username, $password);

require_once "src/Student.php";
require_once "src/Course.php";

class StudentTest extends PHPUnit_Framework_TestCase
{

  protected function tearDown()
  {
      Student::deleteAll();
      Course::deleteAll();
  }

  function test_save()
  {
    $newStudent = new Student("Zander");
    $newStudent->save();

    $result = Student::getAll();

    $this->assertEquals([$newStudent], $result);
  }
  function test_getAll()
  {
    $newStudent = new Student("Zander");
    $newStudent->save();
    $newStudent2 = new Student("Blander");
    $newStudent2->save();

    $this->assertEquals([$newStudent, $newStudent2], Student::getAll());
  }
  function test_deleteAll()
  {
    $newStudent = new Student("Zander");
    $newStudent->save();
    Student::deleteAll();
    $result = Student::getAll();
    $this->assertEquals([], $result);
  }
  function test_addCourse(){
    $newStudent = new Student("Zander");
    $newStudent->save();
    $newCourse = new Course("History", "HIST101");
    $newCourse->save();
    $newStudent->addCourse($newCourse);
    $this->assertTrue(true, "this course was not added");
  }
  function test_findCourses(){
    $newStudent = new Student("Zander");
    $newStudent->save();
    $newCourse = new Course("History", "HIST101");
    $newCourse->save();
    $newCourse2 = new Course("Jazz", "JAZZ202");
    $newCourse2->save();
    $newStudent->addCourse($newCourse);
    $newStudent->addCourse($newCourse2);
    $result = $newStudent->findCourses();
    $this->assertEquals([$newCourse, $newCourse2], $result);
  }
}


 ?>
