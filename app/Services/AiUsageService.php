<?php

namespace App\Services;

use Exception;
use App\Models\AiAgent;
use Illuminate\Support\Facades\Auth;
use App\Libraries\QueryExceptionLibrary;
use Dipokhalder\Settings\Facades\Settings;

class AiUsageService
{
    public string $agent = "";
    public AiService $aiService;

    public function __construct(AiService $aiService)
    {
        $this->aiService            = $aiService;
        $defaultAiAgent             = Settings::group('site')->get('site_default_ai_agent');
        if ($defaultAiAgent > 0) {
            $agent = AiAgent::find($defaultAiAgent);
            if ($agent) {
                $this->agent = $agent->slug;
            }
        }
    }

    /**
     * @throws Exception
     */
    public function checking(): bool
    {
        try {
            if (Settings::group('site')->get('site_default_ai_agent') > 0 && $this->agent !== "" && $this->aiService->agent($this->agent)->status()) {
                if (Auth::check() && Auth::user()->hasPermissionTo('ai-assistant')) {
                    return true;
                }
            }
            return false;
        } catch (Exception $exception) {
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }
}
