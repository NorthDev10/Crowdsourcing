<?php

namespace App\Http\Controllers;

use App\Review;
use App\Http\Requests\ReviewRequest;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Создаёт новый отзыв
     */
    public function store(ReviewRequest $request)
    {   
        return Review::createReview($request);
    }
}
