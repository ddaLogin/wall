<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriptionStoreRequest;
use App\Interfaces\SubscriptionRepository;
use App\Interfaces\UserRepository;
use App\Services\SubscriptionService;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class SubscriptionController extends Controller
{
    private $subscriptionRepository;
    private $userRepository;

    /**
     * SubscriptionController constructor.
     * @param SubscriptionRepository $subscriptionRepository
     * @param UserRepository $userRepository
     */
    public function __construct(SubscriptionRepository $subscriptionRepository, UserRepository $userRepository)
    {
        $this->subscriptionRepository = $subscriptionRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * toggle user subscription
     *
     * @param SubscriptionStoreRequest $request
     * @param SubscriptionService $subscriptionService
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggle(SubscriptionStoreRequest $request, SubscriptionService $subscriptionService)
    {
        $targetUser = $this->userRepository->getById($request->input('target_id'));
        if(Auth::guest() || !Auth::user()->can('subscribe', $targetUser)){
            throw new AccessDeniedHttpException("Could not subscribe on {$targetUser->id}");
        }

        $subscription = $subscriptionService->toggleSubscription(Auth::user()->id, $request->input('target_id'));

        return response()->json([
            'subscription' => $subscription,
            'count' => $this->subscriptionRepository->getByTarget($request->input('target_id'))->count(),
        ], 200);
    }
}
