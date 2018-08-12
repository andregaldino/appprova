<?php
namespace Tests\Repositories;

use App\Models\Course;
use App\Models\Student;
use Tests\TestCase;
use App\Repositories\Student\StudentRepository;
use App\Repositories\Student\StudentRepositoryContract;
use Laravel\Lumen\Testing\DatabaseMigrations;


class StudentTest extends  TestCase
{
	use DatabaseMigrations;
	private $repository;
	private $students;
	
	public function setUp()
	{
		parent::setUp();
		app()->bind(
			StudentRepositoryContract::class,
			StudentRepository::class
		);
		
		$this->repository = app()->make(StudentRepositoryContract::class);
		
		$this->students = factory(Student::class,20)->create();
	}
	
	public function testCreate()
	{
		$student = $this->repository->create([
			'name' => 'André Galdino',
		]);
		
		$this->seeInDatabase('students', [
			'name' => 'André Galdino',
		]);
	}
	
	public function testGelAll()
	{
		
		$students = $this->repository->all();
		
		$this->assertCount(20, $students);
		
	}
	
	public function testUpdate()
	{
		$studentFaker = $this->students->get(5);
		
		$this->seeInDatabase('students',[
			'name' => $studentFaker->name,
		]);
		
		$this->repository->update($studentFaker->id, [
			'name' => 'Antonio Galdino',
		]);
		
		$this->notSeeInDatabase('students', [
			'name'  =>  $studentFaker->name,
			'id'    =>  $studentFaker->id
		]);
		
		$this->seeInDatabase('students',[
			'name' => 'Antonio Galdino',
			'id' => $studentFaker->id
		]);
	}
	
	public function testDelete()
	{
		$studentFaker = $this->students->get(3);
		
		$this->seeInDatabase('students',[
			'id' => $studentFaker->id
		]);
		
		$this->repository->remove($studentFaker->id);
		
		$this->notSeeInDatabase('students',[
			'id' => $studentFaker->id
		]);
	}
	
	public function testAddStudentToCourse()
	{
		$course = factory(Course::class)->create();
		$studentFaker = $this->students->get(2);
		$student = $this->repository->subscriptionCourse($studentFaker->id, $course);
		
		$this->assertTrue($student->courses->contains($course));
	}
	
	
	public function testAddGradeStudentByCourse()
	{
		$courses = factory(Course::class,3)->create();
		$studentFaker = $this->students->get(10);
		$grade = 3;
		$course = $courses->get(2);
		$this->repository->subscriptionCourse($studentFaker->id, $course);
		$this->assertTrue($this->repository->addGradeCourse($studentFaker->id, $course->id, $grade));
		$this->assertEquals(3, $studentFaker->courses()->where('course_id', $course->id)->first()->pivot->grade);
	}
}