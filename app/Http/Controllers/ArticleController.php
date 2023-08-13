<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use OpenApi\Annotations as OA;

class ArticleController extends Controller
{

  /**
     * @OA\Get(
     *     path="/api/articles",
     *     operationId="getArticles",
     *     tags={"Articles"},
     *     summary="Get a list of articles",
     *     @OA\Response(response="200", description="List of articles"),
     *     @OA\RequestBody(
     *         description="Optional request body for filtering",
     *         required=false,
     *     )
     * )
     */

    public function index()
    {
        $articles = Article::all();
        return response()->json(['data' => $articles ], 200);
    }

    public function show($id)
    {
        $article = Article::find($id);
        if($article){
            return response()->json(['data' => $article ], 200);
        }else{
            return response()->json(['msg' => 'Article is not found' ], 404);
        }
    }


     /**
     * @OA\Post(
     *     path="/api/articles",
     *     tags={"Article"},
     *     summary="Create Article",
     *     description="This can only be done by the logged in user.",
     *     operationId="createArticle",
     *     @OA\Response(
     *         response="default",
     *         description="successful operation"
     *     ),
     *     @OA\RequestBody(
     *         description="Create article object",
     *         required=true,
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="title", type="string"),
     *         @OA\Property(property="description", type="string"),
     *     )
     *     )
     * )
     */


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        try{

            $article = Article::create([
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
            ]);

            if($article){
                return response()->json(['data' => $article ], 200);
            }else{
                return response()->json(['msg' => 'Article is not created Successfully.'], 400);
            }
        }catch(\Exception $e){
            return response()->json(['msg' => 'Something went wrong'], 500);
        }



    }

     /**
     * @OA\Put(
     *     path="/api/articles/{id}",
     *     operationId="updateArticle",
     *     tags={"Articles"},
     *     summary="Update an article",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the article",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         description="Article data to update",
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="description", type="string"),
     *         )
     *     ),
     *     @OA\Response(response="201", description="Article updated successfully"),
     *     @OA\Response(response="500", description="Something went wrong"),
     * )
     */

    public function update(Request $request, $id)
    {
        try{
            $article = Article::find($id);

            $article->title = $request->get('title');
            $article->description = $request->get('description');

            $article->save();

            return response()->json(['msg' => 'Article has been updated successfully'], 201);

        }catch(\Exception $e){
            return response()->json(['msg' => 'Something went wrong'], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/articles/{id}",
     *     operationId="deleteArticle",
     *     tags={"Articles"},
     *     summary="Delete an article",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the article",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Article deleted successfully"),
     *     @OA\Response(response="404", description="Article not found"),
     *     @OA\Response(response="500", description="Something went wrong"),
     * )
     */

    public function destroy($id)
    {
        try{
            $article = Article::find($id);
            $article->delete();
            return response()->json(['msg' => 'Article has been deleted successfully'], 200);
        }catch(\Exception $e){
            return response()->json(['msg' => 'Something went wrong'], 500);
        }
    }
}
