<?php

namespace App\Services;

use App\Models\Course;
use App\Repositories\Base\Search\SearchableRepository;
use App\Repositories\Course\CourseRepository;
use App\Repositories\ElasticSearch\BaseElasticSearchRepository;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelHelper;

use Elastic\Elasticsearch\Client;

class CourseService
{
    use ModelHelper;

    protected CourseRepository $courseRepository;
    protected $searchableRepository;

    public function __construct(CourseRepository $courseRepository, SearchableRepository $searchableRepository = null)
    {
        $this->courseRepository = $courseRepository;
        $this->searchableRepository = $searchableRepository ?? new BaseElasticSearchRepository(app(Client::class), new Course());
    }

    public function getAll()
    {
        if (request()->has('search_values')) {
            return $this->searchableRepository->search(request('search_values'), ['teacher', 'assignments']);
        } else {
            return $this->courseRepository->all(['*'], ['teacher', 'assignments']);
        }
    }

    public function find($courseId)
    {
        return $this->courseRepository->findOrFail($courseId);
    }

    public function create($validatedData)
    {
        DB::beginTransaction();

        $course = $this->courseRepository->create($validatedData);

        DB::commit();

        return $course;
    }

    public function update($validatedData, $courseId)
    {
        DB::beginTransaction();

        $this->courseRepository->update($validatedData, ['id' => $courseId]);

        DB::commit();

        return true;
    }

    public function delete($courseId)
    {
        DB::beginTransaction();

        $this->courseRepository->destroy(['id' => $courseId]);

        DB::commit();

        return true;
    }
}
