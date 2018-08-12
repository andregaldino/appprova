<?php
namespace Tests\Repositories;

use App\Models\Institution;
use Tests\TestCase;
use App\Repositories\Institution\InstitutionRepository;
use App\Repositories\Institution\InstitutionRepositoryContract;
use Laravel\Lumen\Testing\DatabaseMigrations;

class InstitutionTest extends  TestCase
{
	use DatabaseMigrations;
	private $repository;
	private $institutionsFaker;
	
	public function setUp()
	{
		parent::setUp();
		app()->bind(
			InstitutionRepositoryContract::class,
			InstitutionRepository::class
		);
		$this->repository = app()->make(InstitutionRepositoryContract::class);
		
		$this->institutionsFaker = factory(Institution::class,20)->create();
	}
	
	public function testCreate()
	{
		$institution = $this->repository->create([
			'name' => 'Instituto Federal'
		]);
		
		$this->assertEquals(
			'Instituto Federal', $institution->name
		);
	}
	
	public function testFindByName()
	{
		$institutionFaker = $this->institutionsFaker->get(2);
		
		$institution = $this->repository->findByName($institutionFaker->name);
		
		$this->assertEquals($institutionFaker->name, $institution->name);
		
	}
	
	public function testGelAll()
	{
		$institutions = $this->repository->all();
		
		$this->assertCount(20, $institutions);
		
	}
	
	public function testUpdate()
	{
		$institutionFaker = $this->institutionsFaker->get(2);
		
		$this->repository->update($institutionFaker->id, [
			'name' => 'Universidade Federal'
		]);
		
		$this->seeInDatabase('institutions',[
			'name' => 'Universidade Federal',
			'id' => $institutionFaker->id
		]);
	}
	
	public function testDelete()
	{
		$institutionFaker = $this->institutionsFaker->get(2);
		
		$this->repository->remove($institutionFaker->id);
		
		$this->notSeeInDatabase('institutions',[
			'id' => $institutionFaker->id
		]);
		
	}
	
	public function testSearchByPartOfName()
	{
		$institutionFaker = $this->institutionsFaker->get(1);
		
		$institutions = $this->repository->searchByName(substr($institutionFaker->name, 0 ,-3));
		
		$this->assertTrue($institutions->contains($institutionFaker));
	}
	
	public function testGradeInstitution()
	{
		$institutionFaker = factory(Institution::class)->create([
			'name' => 'Impacta',
			'grade' => 5,
		]);
		$this->seeInDatabase('institutions',[
			'grade' => 5,
			'name' => 'Impacta',
			'id' => $institutionFaker->id
		]);
	}
	
	public function testOrderByGrade()
	{
		$institutionsFakerOrdered = $this->institutionsFaker->sortByDesc('grade');
		$institutionsOrdered = $this->repository->allOrderedByGrade();
		$this->assertEquals($institutionsFakerOrdered->pluck('grade'), $institutionsOrdered->pluck('grade'));
	}
	
	public function testFilterByGrade()
	{
		$grade = 4;
		$institutionsFakerFiltered = $this->institutionsFaker->where('grade', $grade);
		$institutionsFiltered = $this->repository->filterByGrade($grade);
		$this->assertEquals($institutionsFakerFiltered->pluck('id'), $institutionsFiltered->pluck('id'));
	}
}