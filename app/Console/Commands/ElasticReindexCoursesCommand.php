<?php

namespace App\Console\Commands;

use App\Models\Course;
use Elastic\Elasticsearch\Client;
use Illuminate\Console\Command;

class ElasticReindexCoursesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:courses-reindex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Indexes all courses to Elasticsearch';

    /** @var \Elastic\Elasticsearch\Client */
    private $elasticsearch;

    /** @var Course */
    private $model;

    public function __construct(Client $elasticsearch, Course $course)
    {
        parent::__construct();

        $this->elasticsearch = $elasticsearch;
        $this->model = $course;
    }

    public function handle()
    {
        $counter = 0;

        $indexName = $this->model->getSearchIndex();

        // Check if the index exists, if not, create it
        if (!$this->elasticsearch->indices()->exists(['index' => $indexName])) {
            $this->elasticsearch->indices()->create(['index' => $indexName]);
            $this->info("Created index: $indexName");
        }

        // Now you can safely delete the existing index
        $this->elasticsearch->indices()->delete(['index' => $indexName]);

        $this->info("\nCourses Count  : " . $this->model->count());
        $this->info('Indexing all courses. This might take a while...');

        foreach ($this->model->cursor() as $course) {
            $this->elasticsearch->index([
                'index' => $course->getSearchIndex(), // Get the index name
                'id' => $course->getKey(),            // Unique identifier for the document
                'body' => $course->toSearchArray(),   // Data to be indexed
            ]);

            $counter++;
            $this->info("Indexed course #{$counter} (ID: {$course->getKey()})");
        }

        $this->info("\nDone!");
    }
}
