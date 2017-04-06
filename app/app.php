<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Course.php";
    require_once __DIR__.'/../src/Student.php';

    use Symfony\Component\Debug\Debug;
    Debug::enable();

    $app = new Silex\Application();
    $DB = new PDO('mysql:host=localhost:8889;dbname=registrar', 'root', 'root');
    $app['debug'] = true;

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views'
    ));

    $app->get("/", function() use ($app) {
    return $app['twig']->render('index.html.twig');
    });

    $app->get("/addclass", function() use($app) {
      return $app['twig']->render('addclass.html.twig');
    });

    $app->post("/addclass", function() use($app) {
      $newClass = new Course($_POST['classname'], $_POST['course_number']);
      $newClass->save();
      return $app['twig']->render('seeclasses.html.twig', array('classes'=>Course::getAll()));
    });

    $app->get("/addstudent", function() use($app) {
      return $app['twig']->render('addstudent.html.twig');
    });

    $app->post("/addstudent", function() use($app) {
      $newStudent = new Student($_POST['studentname']);
      $newStudent->save();
      return $app['twig']->render('seestudents.html.twig', array('students'=>Student::getAll()));
    });

    $app->get("/student/{id}", function($id) use($app) {
      $student = Student::find($id);
      $courses = Course::getAll();
      return $app['twig']->render('student.html.twig', array('student'=>$student, 'courses'=>$courses));
    });

    $app->post("/student/{id}", function($id) use($app) {
      $student = Student::find($id);
      $results = $_POST['course_select'];
      foreach($results as $result){
          $student->addCourse($result);
      }
      $student_courses = $student->findCourses();
      return $app['twig']->render('indiv_student.html.twig', array('student'=>$student, 'courses'=>$student_courses));
    });

    $app->post("/addstudent", function() use($app) {
      return $app['twig']->render('addstudent.html.twig');
    });

    $app->get("/seeclasses", function() use($app) {
      return $app['twig']->render('seeclasses.html.twig', array('classes'=>Course::getAll()));
    });

    $app->get("/seestudents", function() use($app) {
      return $app['twig']->render('seestudents.html.twig', array('students'=>Student::getAll()));
    });

    return $app;
 ?>
