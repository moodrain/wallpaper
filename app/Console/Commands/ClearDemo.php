<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ClearDemo extends Command
{
    protected $signature = 'clearDemo {all=0}';
    protected $description = 'clear demo';

    public function handle()
    {
        $files = [
            // controllers
            'app/Http/Controllers/SubjectController.php',
            'app/Http/Controllers/Admin/CommentController.php',
            'app/Http/Controllers/Admin/SubjectCategoryController.php',
            'app/Http/Controllers/Admin/SubjectController.php',

            // models
            'app/Models/Comment.php',
            'app/Models/Subject.php',
            'app/Models/Subject',

            // views
            'resources/views/admin/subject',
            'resources/views/admin/subject-category',
            'resources/views/admin/comment',
            'resources/views/subject',

            // migrations
            'database/migrations/2020_08_05_181933_create_subjects_table.php',
            'database/migrations/2020_08_05_182137_create_subject_categories_table.php',
            'database/migrations/2020_08_05_182216_create_comments_table.php',
        ];
        if ($this->argument('all')) {
            $files = array_merge($files, [
                // explorer
                'app/Http/Controllers/Admin/ExplorerController.php',
                'resources/views/admin/explorer',
            ]);
        }
        foreach($files as $file) {
            $path = base_path($file);
            if (! File::exists($path)) {
                continue;
            }
            File::isDirectory($path) && File::deleteDirectory($path);
            File::isFile($path) && File::delete($path);
        }

    }
}
