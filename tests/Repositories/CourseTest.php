<?php
namespace Tests\Repositories;

use App\Models\Course;
use App\Models\Institution;
use App\Models\Student;
use Tests\TestCase;
use App\Repositories\Course\CourseRepository;
use App\Repositories\Course\CourseRepositoryContract;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;


class CourseTest extends  TestCase
{
	use DatabaseMigrations;
	private $repository;
	
	public function setUp()
	{
		parent::setUp();
		app()->bind(
			CourseRepositoryContract::class,
			CourseRepository::class
		);
		$this->repository = app()->make(CourseRepositoryContract::class);
	}
	
	public function testCreate()
	{
		$institution = factory(Institution::class)->create();
		
		$course = $this->repository->create([
			'name' => 'Analise de Sistemas',
			'institution_id' => $institution->id
		]);
		$this->seeInDatabase('courses', [
			'name' => 'Analise de Sistemas',
			'institution_id' => $institution->id
		]);
	}
	
	public function testFindByName()
	{
		$coursesFaker = factory(Course::class,10)->create();

		$course = $this->repository->findByName($coursesFaker->first()->name);

		$this->assertEquals($coursesFaker->first()->name, $course->name);
		$this->seeInDatabase('courses', [
			'name' => $coursesFaker->first()->name,
			'institution_id' => $coursesFaker->first()->institution_id
		]);
	}
	
	public function testGelAll()
	{
		factory(Course::class,20)->create();
		
		$courses = $this->repository->all();
		
		$this->assertCount(20, $courses);
		
	}
	
	public function testUpdate()
	{
		$institutionsFaker = factory(Institution::class,5)->create();
		$coursesFaker = factory(Course::class,10)->create();
		
		
		$this->repository->update($coursesFaker->get(5)->id, [
			'name' => 'Ciencia da Computacao',
			'institution_id' => $institutionsFaker->get(2)->id
		]);
		
		$this->seeInDatabase('courses',[
			'name' => 'Ciencia da Computacao',
			'institution_id' => $institutionsFaker->get(2)->id,
			'id' => $coursesFaker->get(5)->id
		]);
	}
	
	public function testUpdateInstitution()
	{
		$institutionsFaker = factory(Institution::class, 5)->create();
		$coursesFaker = factory(Course::class, 10)->create();
		
		
		$this->repository->update($coursesFaker->get(5)->id, [
			'institution_id' => $institutionsFaker->get(2)->id
		]);
		
		$this->seeInDatabase('courses', [
			'name' => $coursesFaker->get(5)->name,
			'institution_id' => $institutionsFaker->get(2)->id,
			'id' => $coursesFaker->get(5)->id
		]);
	}
	
	public function testDelete()
	{
		$coursesFaker = factory(Course::class,5)->create();
		
		$this->seeInDatabase('courses',[
			'id' => $coursesFaker->get(1)->id
		]);
		
		$this->repository->remove($coursesFaker->get(1)->id);
		
		$this->notSeeInDatabase('courses',[
			'id' => $coursesFaker->get(1)->id
		]);
	}
	
	public function testSearchByPartOfName()
	{
		$coursesFaker = factory(Course::class,10)->create();
		
		$courses = $this->repository->searchByName(substr($coursesFaker->get(1)->name, 0 ,-3));
		$this->assertTrue($courses->contains($coursesFaker->get(1)));
	}
	
	public function testAddStudentsToCourse()
	{
		$coursesFaker = factory(Course::class,10)->create();
		$studentsFaker = factory(Student::class,10)->create();
		$studentsFaker1 = factory(Student::class,20)->create();
		
		$studentsIds = $studentsFaker->pluck('id')->toArray();
		
		$course = $this->repository->addStudents($coursesFaker->get(0)->id, $studentsIds);
		
		$this->assertCount(10,
			$course
				->students
				->whereIn('id',$studentsIds)
		);
		
		$studentsFaker->each(function($student) use ($course){
			$this->seeInDatabase('student_course', [
				'student_id' => $student->id,
				'course_id' =>  $course->id
			]);
		});
	}
	
	public function testAverageGrade()
	{
		$courseFaker = factory(Course::class)->create();
		
		$student = factory(Student::class)->create();
		$student1 = factory(Student::class)->create();
		$student2 = factory(Student::class)->create();
		$courseFaker->students()->sync([
			$student->id => ['grade' => 8],
			$student1->id => ['grade' => 10],
			$student2->id => ['grade' => 2]
		]);
		
		$this->assertEquals(6.7, $this->repository->averageGradeByCourse($courseFaker->id));
		
		$courseFaker1 = factory(Course::class)->create();
		
		$studentFaker = factory(Student::class)->create();
		$studentFaker1 = factory(Student::class)->create();
		$studentFaker2 = factory(Student::class)->create();
		$studentFaker3 = factory(Student::class)->create();
		$studentFaker4 = factory(Student::class)->create();
		$courseFaker1->students()->sync([
			$studentFaker->id => ['grade' => 2],
			$studentFaker1->id => ['grade' => 4],
			$studentFaker2->id => ['grade' => 7],
			$studentFaker3->id => ['grade' => 7],
			$studentFaker4->id => ['grade' => 3],
		]);
		
		$this->assertEquals(4.6, $this->repository->averageGradeByCourse($courseFaker1->id));
	}
	
	public function testGradeCourse()
	{
		$institutionFaker = factory(Institution::class)->create();
		$courseFaker = factory(Course::class)->create([
			'name' => 'Analise e Desenvolivmento de Sistemas',
			'grade' => 4,
			'institution_id' => $institutionFaker->id
		]);
		$this->seeInDatabase('courses',[
			'grade' => 4,
			'institution_id' => $institutionFaker->id
		]);
	}
	
}