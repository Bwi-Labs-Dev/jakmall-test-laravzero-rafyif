<?php

namespace App\Commands;

use App\Services\ReviewService;
use LaravelZero\Framework\Commands\Command;
use Illuminate\Support\Facades\Cache;

class ReviewProductCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'review:product {productId : Id for product}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'List review of product';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $key = "product_{$this->argument('productId')}";
            $data = Cache::get($key);
            if (!$data) {
                $data = ReviewService::getReviewByProductId($this->argument('productId'));
                $data = json_encode($data, JSON_PRETTY_PRINT);
                Cache::put($key, $data);
            }

        } catch (Throwable $exception) {
            $this->warn(
                string: $exception->getMessage(),
            );

            return ReviewProductCommand::FAILURE;
        }
        
        $this->info("!========= Review Products =========!");
        $this->info($data);
        return $data;
    }
}
