<?php

namespace App\Commands;

use App\Services\ReviewService;
use LaravelZero\Framework\Commands\Command;

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
            $data = ReviewService::getReviewSummary();
            $reviewSummary = json_encode($data, JSON_PRETTY_PRINT);
        } catch (Throwable $exception) {
            $this->warn(
                string: $exception->getMessage(),
            );

            return ReviewSummaryCommand::FAILURE;
        }
        
        $this->info("!========= Review Summary Results =========!");
        $this->info($reviewSummary);
        return ReviewSummaryCommand::SUCCESS;
    }
}
