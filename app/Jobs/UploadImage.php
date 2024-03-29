<?php

namespace App\Jobs;

use File;
use Storage;
use App\Models\Channel;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UploadImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $channel;

    public $fileId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Channel $channel, $fileId)
    {
        $this->channel = $channel;
        $this->fileId = $fileId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $path = storage_path(). '/uploads/' . $this->fileId;
        $fileName = $this->fileId . '.png';

        if(Storage::disk('s3images')->put('profile/' . $fileName, fopen($path, 'r+'))){
                File::delete($path);
            }
        
        $this->channel->image_fileName = $fileName;
        $this->channel->save();
    }
}
