<?php
class Course
{
  private $name;
  private $course_number;
  private $id;

  function __construct($name, $course_number, $id= null)
  {
    $this->name = $name;
    $this->course_number = $course_number;
    $this->id = $id;
  }
  function getName()
  {
    return $this->name;
  }
  function setName($new_name)
  {
    $this->name = $new_name;
  }
  function getCourse()
  {
    return $this->course_number;
  }
  function setCourse($new_course)
  {
    $this->course = $new_course;
  }
  function getId()
  {
    return $this->id;
  }
  function save()
  {
    $executed = $GLOBALS['DB']->exec("INSERT INTO classes (name, course_number) VALUES ('{$this->getName()}','{$this->getCourse()}')");
    if($executed){
      $this->id = $GLOBALS['DB']->lastInsertId();
      return true;
    }else{
      return false;
    }
  }
  static function getAll()
  {
      $courses = array();
      $returned_courses = $GLOBALS['DB']->query("SELECT * FROM classes;");
      foreach ($returned_courses as $course)
      {
          $name = $course['name'];
          $course_number = $course['course_number'];
          $id = $course['id'];
          $newCourse = new Course($name, $course_number, $id);
          array_push($courses, $newCourse);
      }
      return $courses;
  }
  function findStudents()
  {
    $returned_students = $GLOBALS['DB']->query("SELECT students.* FROM classes JOIN classes_students ON (classes_students.course_id = classes.id) JOIN students ON (students.id = classes_students.students_id) WHERE classes.id = {$this->getId()};");
    $students = array();
    foreach($returned_students as $student) {
      $newStudent = new Student($student['name'], $student['date_enrolled'], $student['id']);
      array_push($students, $newStudent);
    }
    return $students;
  }
  static function deleteAll()
  {
      $executed = $GLOBALS['DB']->exec("DELETE FROM classes;");
      if ($executed)
      {
          return true;
      } else {
          return false;
      }
  }
  function addStudent($student){
    $executed = $GLOBALS['DB']->exec("INSERT INTO classes_students (student_id, course_id) VALUES ({$student->getId()}, {$this->getId()});");
    if($executed){
      return true;
    }else{
      return false;
    }
  }
}
?>
