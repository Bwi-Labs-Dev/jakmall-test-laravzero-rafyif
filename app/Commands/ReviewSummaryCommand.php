<?php

namespace App\Commands;

use App\Services\ReviewService;
use LaravelZero\Framework\Commands\Command;
use Illuminate\Support\Facades\Cache;

class ReviewSummaryCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'review:summary';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Summary list of review';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $data = Cache::get('review_summary');
            if (!$data) {
                $data = ReviewService::getReviewSummary();
                $data = json_encode($data, JSON_PRETTY_PRINT);
                Cache::put('review_summary', $data);
            }

        } catch (Throwable $exception) {
            $this->warn(
                string: $exception->getMessage(),
            );

            return ReviewSummaryCommand::FAILURE;
        }
        
        $this->info("!========= Review Summary Results =========!");
        $this->info($data);
        return $data;
    }
}
