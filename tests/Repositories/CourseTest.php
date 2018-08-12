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
	private $coursesFaker;
	
	public function setUp()
	{
		parent::setUp();
		
		app()->bind(
			CourseRepositoryContract::class,
			CourseRepository::class
		);
		$this->repository = app()->make(CourseRepositoryContract::class);
		
		$this->coursesFaker = factory(Course::class,20)->create();
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
		$courseFaker = $this->coursesFaker->first();
		$course = $this->repository->findByName($courseFaker->name);

		$this->assertEquals($courseFaker->name, $course->name);
		$this->seeInDatabase('courses', [
			'name' => $courseFaker->name,
			'institution_id' => $courseFaker->institution_id
		]);
	}
	
	public function testGelAll()
	{
		$courses = $this->repository->all();
		
		$this->assertCount(20, $courses);
		
	}
	
	public function testUpdate()
	{
		$institutionsFaker = factory(Institution::class,5)->create();
		$courseFaker = $this->coursesFaker->get(5);
		$institutionFaker = $institutionsFaker->get(2);
		
		$this->repository->update($courseFaker->id, [
			'name' => 'Ciencia da Computacao',
			'institution_id' => $institutionFaker->id
		]);
		
		$this->seeInDatabase('courses',[
			'name' => 'Ciencia da Computacao',
			'institution_id' => $institutionFaker->id,
			'id' => $courseFaker->id
		]);
	}
	
	public function testUpdateInstitution()
	{
		$institutionsFaker = factory(Institution::class, 5)->create();
		$institutionFaker = $institutionsFaker->get(2);
		$courseFaker = $this->coursesFaker->get(5);
		
		$this->repository->update($courseFaker->id, [
			'institution_id' => $institutionFaker->id
		]);
		
		$this->seeInDatabase('courses', [
			'name' => $courseFaker->name,
			'institution_id' => $institutionFaker->id,
			'id' => $courseFaker->id
		]);
	}
	
	public function testDelete()
	{
		$courseFaker = $this->coursesFaker->get(1);
		$this->seeInDatabase('courses',[
			'id' => $courseFaker->id
		]);
		
		$this->repository->remove($courseFaker->id);
		
		$this->notSeeInDatabase('courses',[
			'id' => $courseFaker->id
		]);
	}
	
	public function testSearchByPartOfName()
	{
		$courseFaker = $this->coursesFaker->get(1);
		$courses = $this->repository->searchByName(substr($courseFaker->name, 0 ,-3));
		$this->assertTrue($courses->contains($courseFaker));
	}
	
	public function testAddStudentsToCourse()
	{
		$studentsFaker = factory(Student::class,10)->create();
		
		$studentsIds = $studentsFaker->pluck('id')->toArray();
		
		$courseFaker = $this->coursesFaker->get(3);
		
		$course = $this->repository->addStudents($courseFaker->id, $studentsIds);
		
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
		$courseFaker = $this->coursesFaker->get(5);
		
		$student = factory(Student::class)->create();
		$student1 = factory(Student::class)->create();
		$student2 = factory(Student::class)->create();
		$courseFaker->students()->sync([
			$student->id => ['grade' => 8],
			$student1->id => ['grade' => 10],
			$student2->id => ['grade' => 2]
		]);
		
		$this->assertEquals(6.7, $this->repository->averageGradeByCourse($courseFaker->id));
		
		$courseFaker1 = $this->coursesFaker->get(6);
		
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
	
	
	public function testFilterByGrade()
	{
		$grade = 4;
		$coursesFakerFiltered = $this->coursesFaker->where('grade', $grade);
		$coursesFiltered = $this->repository->filterByGrade($grade);
		$this->assertEquals($coursesFakerFiltered->pluck('id'), $coursesFiltered->pluck('id'));
	}
}