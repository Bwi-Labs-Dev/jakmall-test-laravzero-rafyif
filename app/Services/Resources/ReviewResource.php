<?php

declare(strict_types=1);

namespace App\Services\Resources;

use Illuminate\Support\Facades\File;

class ReviewResource
{
    public static function listProductReview()
    {
        $productContents = File::get(base_path('database/products.json'));
        $productJson = json_decode(json: $productContents, associative: true);

        $reviewContents = File::get(base_path('database/reviews.json'));
        $reviewJson = json_decode(json: $reviewContents, associative: true);

        $reviews = [];
        foreach ($reviewJson as $review) 
        {
            $productId = $review["product_id"];
            if (!isset($reviews[$productId])) {
                # find product by id
                $key = array_search($productId, array_column($productJson, "id"));

                # define product
                $reviews[$productId] = $productJson[$key];

                # initialize array for grouping by product rating
                $reviews[$productId]["rating"] = [];
            }
            
            if (!isset($reviews[$productId]["rating"][$review["rating"]])) {
                # initialize count by rating
                $reviews[$productId]["rating"][$review["rating"]] = 1;
            } else {
                $reviews[$productId]["rating"][$review["rating"]] += 1;
            }
        }

        return $reviews;
    }
}