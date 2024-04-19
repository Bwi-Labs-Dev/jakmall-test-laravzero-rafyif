<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\Resources\ReviewResource;

class ReviewService
{
    public static function averageFormat($ratings, $totalReviews)
    {
        # sort rating by key
        krsort($ratings);

        $totalRatings = 0;
        $ratingList = [];
        foreach ($ratings as $key => $value) 
        {
            $ratingList["{$key}_star"] = $value;
            $totalRatings += (int) $key * $value;
        }

        $result["total_reviews"] = $totalReviews;
        $result["average_ratings"] = (float) number_format(($totalRatings / $totalReviews), 1);
        $result = (object) array_merge($result, $ratingList);

        return $result;
    }

    public static function getReviewSummary()
    {
        $productReviews = ReviewResource::listProductReview();
        
        $totalReviews = 0;
        $ratings = [];
        foreach ($productReviews as $review) 
        {
            foreach ($review["rating"] as $key => $value) 
            {
                if (!isset($ratings[$key])) {
                    $ratings[$key] = 0;
                }

                $ratings[$key] += $value;
                $totalReviews += $value;
            }
        }

        $result = self::averageFormat($ratings, $totalReviews);

        return $result;
    }
    
    public static function getReviewByProductId($productId)
    {
        $productReviews = ReviewResource::listProductReview();
        
        $totalReviews = 0;
        $ratings = [];

        foreach ($productReviews[$productId]["rating"] as $key => $value) 
        {
            if (!isset($ratings[$key])) {
                $ratings[$key] = 0;
            }

            $ratings[$key] += $value;
            $totalReviews += $value;
        }

        $result = self::averageFormat($ratings, $totalReviews);

        return $result;
    }
}