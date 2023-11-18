<?php

namespace App\Console\Commands;

use App\Models\Uploads;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DeleteOldUploads extends Command
{

    protected $signature = 'app:delete-old-uploads';


    public function handle()
    {
        $cutoffTime = Carbon::now()->subDay();

        $uploadsToDelete = Uploads::whereNull('post_id')
            ->where('updated_at', '<', $cutoffTime)
            ->get();

        foreach ($uploadsToDelete as $upload) {
            if ($upload->image) {
                $upload->image->deleteImageFile();
                $upload->image->delete();
            }
            Storage::disk('local')->delete($upload->url);
            $upload->delete();
        }

        $this->info('Old uploads deleted successfully.');
    }
}
