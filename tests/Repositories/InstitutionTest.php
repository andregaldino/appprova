<?php
namespace Tests\Repositories;

use App\Models\Institution;
use Tests\TestCase;
use App\Repositories\Institution\InstitutionRepository;
use App\Repositories\Institution\InstitutionRepositoryContract;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;


class InstitutionTest extends  TestCase
{
	use DatabaseMigrations;
	private $repository;
	
	public function setUp()
	{
		parent::setUp();
		app()->bind(
			InstitutionRepositoryContract::class,
			InstitutionRepository::class
		);
		$this->repository = app()->make(InstitutionRepositoryContract::class);
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
		$institutionFaker = factory(Institution::class)->create();
		
		$institution = $this->repository->findByName($institutionFaker->name);
		
		$this->assertEquals($institutionFaker->name, $institution->name);
		
	}
	
	public function testGelAll()
	{
		factory(Institution::class,20)->create();
		
		$institutions = $this->repository->all();
		
		$this->assertCount(20, $institutions);
		
	}
	
	public function testUpdate()
	{
		$institutions = factory(Institution::class,5)->create();
		$institutionFaker = $institutions->get(2);
		
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
		$institutions = factory(Institution::class,5)->create();
		$institutionFaker = $institutions->get(2);
		
		$this->repository->remove($institutionFaker->id);
		
		$this->notSeeInDatabase('institutions',[
			'id' => $institutionFaker->id
		]);
		
	}
	
	public function testSearchByPartOfName()
	{
		$institutionsFaker = factory(Institution::class,10)->create();
		$institutionFaker = $institutionsFaker->get(1);
		
		$institutions = $this->repository->searchByName(substr($institutionFaker->name, 0 ,-3));
		
		$this->assertTrue($institutions->contains($institutionFaker));
	}
}