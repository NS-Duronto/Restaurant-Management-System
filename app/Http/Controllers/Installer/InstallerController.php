<?php

namespace App\Http\Controllers\Installer;

use App\Http\Controllers\Controller;
use App\Services\InstallerPermissionCheckerService;
use App\Services\InstallerRequirementsCheckerService;
use App\Services\InstallerService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class InstallerController extends Controller
{
    public InstallerService $installerService;

    protected InstallerRequirementsCheckerService $installerRequirementsCheckerService;

    protected InstallerPermissionCheckerService $installerPermissionCheckerService;

    public function __construct(InstallerService $installerService, InstallerRequirementsCheckerService $installerRequirementsCheckerService, InstallerPermissionCheckerService $installerPermissionCheckerService)
    {
        $this->installerService = $installerService;
        $this->installerRequirementsCheckerService = $installerRequirementsCheckerService;
        $this->installerPermissionCheckerService = $installerPermissionCheckerService;

        if (file_exists(storage_path('installed'))) {
            Redirect::to(env('APP_URL'))->send();
        }
    }

    public function index(): Factory|View|Application
    {
        return view('installer.welcome');
    }

    public function requirement(): Factory|View|Application
    {
        $phpSupportInfo = $this->installerRequirementsCheckerService->checkPHPversion(config('installer.core.minPhpVersion'));
        $requirements = $this->installerRequirementsCheckerService->check(config('installer.requirements'));

        return view('installer.requirement', compact('requirements', 'phpSupportInfo'));
    }

    public function permission(): Factory|View|Application
    {
        $permissions = $this->installerPermissionCheckerService->check(config('installer.permissions'));

        return view('installer.permission', compact('permissions'));
    }

    public function license(): Redirector|RedirectResponse
    {
        return redirect(route('installer.site'));
    }

    public function licenseStore(Request $request): Redirector|RedirectResponse
    {
        return redirect(route('installer.site'));
    }

    public function site(): Factory|View|Application
    {
        return view('installer.site');
    }

    public function siteStore(Request $request): Redirector|RedirectResponse|Application
    {
        $rules = config('installer.site.form.rules');
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect(route('installer.site'))->withErrors($validator)->withInput();
        }

        try {
            $this->installerService->siteSetup($request);

            return redirect(route('installer.database'));
        } catch (Exception $e) {
            return redirect(route('installer.site'))->withErrors($e->getMessage())->withInput();
        }
    }

    public function database(): Factory|View|Application
    {
        return view('installer.database');
    }

    public function databaseStore(Request $request): Redirector|RedirectResponse|Application
    {
        $rules = config('installer.database.form.rules');
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect(route('installer.database'))->withErrors($validator)->withInput();
        }

        try {
            $response = $this->installerService->databaseSetup($request);
            if ($response) {
                return redirect(route('installer.final'));
            }

            return redirect(route('installer.database'))->withErrors(['global' => trans('installer.database.fail_message')])->withInput();
        } catch (Exception $e) {
            return redirect(route('installer.database'))->withErrors(['global' => $e->getMessage()])->withInput();
        }
    }

    public function final(): Factory|View|Application
    {
        return view('installer.final');
    }

    public function finalStore(): Redirector|Application|RedirectResponse
    {
        try {
            $this->installerService->finalSetup();

            return redirect(env('APP_URL'));
        } catch (Exception $e) {
            return redirect(route('installer.site'))->withErrors(['global' => $e->getMessage()]);
        }
    }
}
