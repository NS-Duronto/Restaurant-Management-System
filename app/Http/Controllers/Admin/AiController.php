<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\AiChatResource;
use App\Models\AiAgent;
use App\Models\AiChatHistory;
use App\Models\Branch;
use App\Services\AiChatHistoryService;
use App\Services\AiService;
use App\Traits\DefaultAccessModelTrait;
use Dipokhalder\Settings\Facades\Settings;
use App\Http\Requests\AiRequest;
use Exception;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;
use App\Services\AiUsageService;

class AiController extends AdminController implements HasMiddleware
{
    use DefaultAccessModelTrait;

    public string $agent;
    public AiService $aiService;
    public AiChatHistoryService $aiChatHistoryService;
    public AiUsageService $aiUsageService;

    public function __construct(AiService $aiService, AiChatHistoryService $aiChatHistoryService, AiUsageService $aiUsageService)
    {
        parent::__construct();
        $this->aiService            = $aiService;
        $this->aiChatHistoryService = $aiChatHistoryService;
        $this->aiUsageService       = $aiUsageService;
        $defaultAiAgent             = Settings::group('site')->get('site_default_ai_agent');
        if ($defaultAiAgent > 0) {
            $agent = AiAgent::find($defaultAiAgent);
            if ($agent) {
                $this->agent = $agent->slug;
            }
        }
    }

    public static function middleware(): array
    {
        return [];
    }

    public function name(AiRequest $aiRequest): \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            if ($this->aiUsageService->checking()) {
                return response(['status' => true, 'data' => $this->aiService->agent($this->agent)->name($aiRequest)], 200);
            }
            return response(['status' => false, 'message' => trans('all.message.agent_is_not_active')], 422);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function description(AiRequest $aiRequest): \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            if ($this->aiUsageService->checking()) {
                return response(['status' => true, 'data' => $this->aiService->agent($this->agent)->description($aiRequest)], 200);
            }
            return response(['status' => false, 'message' => trans('all.message.agent_is_not_active')], 422);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function caution(AiRequest $aiRequest): \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            if ($this->aiUsageService->checking()) {
                return response(['status' => true, 'data' => $this->aiService->agent($this->agent)->caution($aiRequest)], 200);
            }
            return response(['status' => false, 'message' => trans('all.message.agent_is_not_active')], 422);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function chat(AiRequest $aiRequest): \Illuminate\Http\Response|AiChatResource|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $branch = Branch::find($this->branch());

            if ($this->aiUsageService->checking()) {
                return new AiChatResource($this->aiChatHistoryService->store($aiRequest, $branch));
            }
            return response(['status' => false, 'message' => trans('all.message.agent_is_not_active')], 422);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function chatResponse(AiChatHistory $aiChatHistory, AiRequest $aiRequest): \Illuminate\Http\Response|AiChatResource|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new AiChatResource($this->aiChatHistoryService->update($aiChatHistory, $this->aiService->agent($this->agent)->message($aiRequest)));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function chatHistory(): \Illuminate\Http\Response|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $branch  = Branch::find($this->branch());
            return AiChatResource::collection($this->aiChatHistoryService->list($branch));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function deleteChatHistory(Branch $branch): \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $chatHistory = AiChatHistory::where(['branch_id' => $branch->id, 'user_id' => Auth::user()->id, 'ai_agent_id' => Settings::group('site')->get('site_default_ai_agent')])->get();
            $chatHistory->each(function ($chat) {
                $chat->delete();
            });
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function status(): \Illuminate\Http\Response|bool|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            if ($this->aiUsageService->checking()) {
                return response(['status' => true, 'data' => true], 200);
            }
            return response(['status' => true, 'data' => false], 200);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

}

