<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function show(Request $request, $param)
    {
        $post = Post::published()
                    ->where(function ($query) use ($param) {
                        $query->where('id', $param)
                              ->orWhere('slug', $param);
                    })
                    ->with('author', 'translations')
                    ->first();

        //if preview, ignore the visibility status
        if ($request->query('preview')) {
            if ($request->query('preview') == true) {
                $post = Post::where('id', $param)
                            ->orWhere('slug', $param)
                            ->with('author')
                            ->without('translations')
                            ->first();
            }
        }
        if ($request->hasHeader("x-localization")) {
            $post->setDefaultLocale($request->header("x-localization"));
        }
        if (!$post) {
            return $this->getResponseData("0", "Record Not Found", "The post you are trying to search for is not availabe.");
        }
        return $this->getResponseData('1', "Success", $post);
    }
}
