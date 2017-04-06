<?php
  class Student
  {
    private $name;
    private $date_enrolled;
    private $id;

    function __construct($name, $date_enrolled=null, $id=null)
    {
      $this->name = $name;
      $this->date_enrolled = $date_enrolled;
      $this->id = $id;
    }
    function setName($newname)
    {
      $this->name = $newname;
    }
    function getName()
    {
      return $this->name;
    }
    function setDate($newdate)
    {
      $this->date_enrolled = $date_enrolled;
    }
    function getDate()
    {
      return $this->date_enrolled;
    }
    function getId()
    {
      return $this->id;
    }
    function save(){
      $executed = $GLOBALS['DB']->exec("INSERT INTO students (name, date_enrolled) VALUES ('{$this->getName()}', NOW());");
      if($executed){
        $this->id = $GLOBALS['DB']->lastInsertId();
        return true;
      }else{
        return false;
      }
    }
    static function getAll(){
      $students = array();
      $returned_students = $GLOBALS['DB']->query("SELECT * FROM students;");
      foreach($returned_students as $student){
        $name = $student['name'];
        $date = $student['date_enrolled'];
        $id = $student['id'];
        $newStudent = new Student($name, $date, $id);
        array_push($students, $newStudent);
      }
      return $students;
    }
    static function deleteAll(){
      $executed = $GLOBALS['DB']->exec("DELETE FROM students;");
      if(!$executed){
        return false;
      }
      $executed = $GLOBALS['DB']->exec("DELETE FROM classes_students;");
      if (!$executed){
        return false;
      }else{
        return true;
      }
    }

    static function find($search_id)
        {
            $found_student = null;
            $returned_students = $GLOBALS['DB']->prepare("SELECT * FROM students WHERE id = :id");
            $returned_students->bindParam(':id', $search_id, PDO::PARAM_STR);
            $returned_students->execute();
            foreach($returned_students as $student){
              $student_name = $student['name'];
              $enroll_date = $student['date_enrolled'];
              $id = $student['id'];
              if($id == $search_id){
                $found_student = new Student($student_name, $enroll_date, $id);
              }
            }
            return $found_student;
        }

    function addCourse($course){
      $executed = $GLOBALS['DB']->exec("INSERT INTO classes_students (student_id, course_id) VALUES ({$this->getId()}, {$course});");
      if($executed){
        return true;
      }else{
        return false;
      }
    }
    function findCourses()
    {
      $query = $GLOBALS['DB']->query("SELECT course_id FROM classes_students WHERE student_id = {$this->getId()};");
      $course_ids = $query->fetchAll(PDO::FETCH_ASSOC);
      $courses= array();
      foreach($course_ids as $course){
        $course_id = $course['course_id'];
        $result = $GLOBALS['DB']->query("SELECT * FROM classes WHERE id = {$course_id};");
        $returned_course = $result->fetchAll(PDO::FETCH_ASSOC);
        $name = $returned_course[0]['name'];
        $course = $returned_course[0]['course_number'];
        $id = $returned_course[0]['id'];
        $newCourse = new Course($name, $course, $id);
        array_push($courses, $newCourse);
      }
      return $courses;
    }

  }
 ?>
