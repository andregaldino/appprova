<?php
namespace Tests\Repositories;

use App\Models\Course;
use App\Models\Student;
use App\Models\Institution;
use App\Repositories\Course\CourseRepository;
use App\Repositories\Course\CourseRepositoryContract;
use Tests\TestCase;
use App\Repositories\Student\StudentRepository;
use App\Repositories\Student\StudentRepositoryContract;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;


class StudentTest extends  TestCase
{
	use DatabaseMigrations;
	private $repository;
	
	public function setUp()
	{
		parent::setUp();
		app()->bind(
			StudentRepositoryContract::class,
			StudentRepository::class
		);
		
		$this->repository = app()->make(StudentRepositoryContract::class);
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
		factory(Student::class,20)->create();
		
		$students = $this->repository->all();
		
		$this->assertGreaterThanOrEqual(20, $students->count());
		
	}
	
	public function testUpdate()
	{
		$studentsFaker = factory(Student::class,10)->create();
		
		$this->seeInDatabase('students',[
			'name' => $studentsFaker->get(5)->name,
		]);
		
		$this->repository->update($studentsFaker->get(5)->id, [
			'name' => 'Antonio Galdino',
		]);
		
		$this->notSeeInDatabase('students', [
			'name'  =>  $studentsFaker->get(5)->name,
			'id'    =>  $studentsFaker->get(5)->id
		]);
		
		$this->seeInDatabase('students',[
			'name' => 'Antonio Galdino',
			'id' => $studentsFaker->get(5)->id
		]);
	}
	
	public function testDelete()
	{
		$studentsFaker = factory(Student::class,5)->create();
		
		$this->seeInDatabase('students',[
			'id' => $studentsFaker->get(1)->id
		]);
		
		$this->repository->remove($studentsFaker->get(1)->id);
		
		$this->notSeeInDatabase('students',[
			'id' => $studentsFaker->get(1)->id
		]);
	}
	
	public function testAddStudentToCourse()
	{
		$course = factory(Course::class)->create();
		$studentFaker = factory(Student::class)->create();
		$student = $this->repository->subscriptionCourse($studentFaker->id, $course);
		
		$this->assertTrue($student->courses->contains($course));
	}
	
	
	public function testAddGradeStudentByCourse()
	{
		$courses = factory(Course::class,3)->create();
		$students = factory(Student::class,15)->create();
		$grade = 3;
		$course = $courses->get(2);
		$this->repository->subscriptionCourse($students->get(10)->id, $course);
		$student = $students->get(10);
		$this->assertTrue($this->repository->addGradeCourse($student->id, $course->id, $grade));
		$this->assertEquals(3, $student->courses()->where('course_id', $course->id)->first()->pivot->grade);
	}
}