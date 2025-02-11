<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Users\{UserCollection, UserResource};
use Illuminate\Routing\Controllers\{HasMiddleware, Middleware};
use App\Http\Requests\Users\{StoreUserRequest, UpdateUserRequest};

class UserController extends Controller implements HasMiddleware
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
    public function index(): UserCollection|UserResource
    {
        $users = User::with(['posts'])
            ->latest()
            ->paginate(request()->query('per_page', 10));

        return (new UserCollection($users))
            ->additional([
                'message' => 'The user was received successfully.',
                'success' => true,
                'status_code' => Response::HTTP_OK
            ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): UserResource|JsonResponse
    {
        $user = User::create($request->validated());
        $user->load(['posts']);

        return (new UserResource($user))
            ->additional([
                'message' => 'The user was created successfully.',
                'success' => true,
                'status_code' => Response::HTTP_CREATED
            ])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string|int $id): UserResource
    {
        $user = User::with(['posts'])->findOrFail($id);

        return (new UserResource($user))
            ->additional([
                'message' => 'The user was received successfully.',
                'success' => true,
                'status_code' => Response::HTTP_OK
            ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string|int $id): UserResource
    {
        $user = User::with(['posts'])->findOrFail($id);
        $user->update($request->validated());

        return (new UserResource($user))
            ->additional([
                'message' => 'The user was updated successfully.',
                'success' => true,
                'status_code' => Response::HTTP_OK
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string|int $id): UserResource|JsonResponse
    {
        try {
            return DB::transaction(function () use ($id) {
                $user = User::with(['posts'])->findOrFail($id);
                $user->delete();
                $user->posts()->delete();

                return (new UserResource(null))
                    ->additional([
                        'message' => 'The user was deleted successfully.',
                        'success' => true,
                        'status_code' => Response::HTTP_OK
                    ]);
            });
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());

            return (new UserResource(null))
                ->additional([
                    'message' => 'Cannot delete user that has posts.',
                    'success' => false,
                    'status_code' => Response::HTTP_INTERNAL_SERVER_ERROR
                ])
                ->response()
                ->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
