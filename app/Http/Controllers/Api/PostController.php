<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Posts\{PostCollection, PostResource};
use Illuminate\Routing\Controllers\{HasMiddleware, Middleware};
use App\Http\Requests\Posts\{StorePostRequest, UpdatePostRequest};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            'auth:sanctum',
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): PostCollection|PostResource
    {
        $posts = Post::with(['user'])
            ->latest()
            ->when($request->filled('search'), function (Builder $q) use ($request) {
                $search = "%$request->search%";
                $q->where('title', 'like', $search)
                    ->orWhere('body', 'like', $search);
            })
            ->paginate(request()->query('per_page', 10));
        return (new PostCollection($posts))
            ->additional([
                'message' => 'The posts was received successfully.',
                'success' => true,
                'status_code' => Response::HTTP_OK
            ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request): PostResource|JsonResponse
    {
        $post = Post::create($request->validated());
        $post->load(['user']);

        return (new PostResource($post))
            ->additional([
                'message' => 'The post was created successfully.',
                'success' => true,
                'status_code' => Response::HTTP_CREATED
            ])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string|int $id): PostResource
    {
        $post = $this->getCachedPost($id);

        return (new PostResource($post))
            ->additional([
                'message' => 'The post was received successfully.',
                'success' => true,
                'status_code' => Response::HTTP_OK
            ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, string|int $id): PostResource
    {
        $post = $this->getCachedPost($id);
        $post->update($request->validated());

        if ($post->isDirty()) {
            Cache::forget("post:$id");
        }

        return (new PostResource($post))
            ->additional([
                'message' => 'The post was updated successfully.',
                'success' => true,
                'status_code' => Response::HTTP_OK
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string|int $id): PostResource|JsonResponse
    {
        try {
            Post::destroy($id);

            return (new PostResource(null))
                ->additional([
                    'message' => 'The post was deleted successfully.',
                    'success' => true,
                    'status_code' => Response::HTTP_OK
                ]);
        } catch (\Exception $e) {
            return (new PostResource(null))
                ->additional([
                    'message' => $e->getMessage(),
                    'success' => false,
                    'status_code' => Response::HTTP_INTERNAL_SERVER_ERROR
                ])
                ->response()
                ->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getCachedPost(string|int $id)
    {
        return Cache::remember("post:$id", 60, function () use ($id) {
            return Post::with(['user'])->findOrFail($id);
        });
    }
}
