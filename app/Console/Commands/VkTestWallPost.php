<?php


namespace App\Console\Commands;


use App\Utils\Vk;
use Illuminate\Console\Command;

class VkTestWallPost extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vk:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Post test message to VK wall';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        Vk::testWallPost();
    }
}