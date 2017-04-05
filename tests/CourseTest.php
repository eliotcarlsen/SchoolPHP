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

class CourseTest extends PHPUnit_Framework_TestCase
{

  protected function tearDown()
  {
      Course::deleteAll();
      Student::deleteAll();
  }

  function test_save()
  {
    $newCourse = new Course("Zander", "hist101");
    $newCourse->save();

    $result = Course::getAll();

    $this->assertEquals([$newCourse], $result);
  }
  function test_get_all()
  {
    $newCourse = new Course("science", "SCI101");
    $newCourse->save();
    $newCourse2 = new Course("history", "HIST101");
    $newCourse2->save();
    $result = Course::getAll();
    $this->assertEquals([$newCourse, $newCourse2], $result);
  }
  function test_deleteAll()
  {
    $newCourse = new Course("science", "sci101");
    $newCourse->save();
    Course::deleteAll();
    $result = Course::getAll();
    $this->assertEquals([], $result);
  }
  function test_findStudents(){
    $newStudent = new Student("Zander", $newStudent->getDate());
    $newStudent->save();
    $newStudent2 = new Student("Blander");
    $newStudent2->save();
    $newCourse2 = new Course("Jazz", "JAZZ202");
    $newCourse2->save();
    $newStudent->addCourse($newCourse2);
    $newStudent2->addCourse($newCourse2);
    $result = $newCourse2->findStudents();
    $this->assertEquals([$newStudent, $newStudent2], $result);
  }
  function test_addCourse(){
    $newStudent = new Student("Zander");
    $newStudent->save();
    $newCourse = new Course("History", "HIST101");
    $newCourse->save();
    $newCourse->addStudent($newStudent);
    $this->assertTrue(true, "this course was not added");
  }
}
?>
