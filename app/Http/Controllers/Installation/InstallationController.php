<?php

namespace App\Http\Controllers\Installation;

use App\Http\Controllers\Controller;
use App\Services\InstallationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class InstallationController extends Controller
{
    protected $installationService;

    public function __construct(InstallationService $installationService)
    {
        $this->installationService = $installationService;
    }

    public function welcome()
    {
        return view('installation.welcome');
    }

    public function requirements()
    {
        $requirements = $this->installationService->checkRequirements();
        $canProceed = !in_array(false, array_column($requirements, 'result'));

        return view('installation.requirements', compact('requirements', 'canProceed'));
    }

    public function database()
    {
        return view('installation.database');
    }

    public function setupDatabase(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hostname' => 'required',
            'database' => 'required',
            'username' => 'required',
            'password' => 'nullable'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $this->installationService->setupDatabase($request->all());
            return redirect()->route('installation.clinic');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    public function clinic()
    {
        return view('installation.clinic');
    }

    public function saveClinic(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'clinic_name' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'phone' => 'required',
            'tax_office' => 'required',
            'tax_number' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $this->installationService->setupClinic($request->all());
            return redirect()->route('installation.admin');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    public function admin()
    {
        return view('installation.admin');
    }

    public function createAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $this->installationService->createAdmin($request->all());
            return redirect()->route('installation.complete');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    public function complete()
    {
        $this->installationService->markAsInstalled();
        return view('installation.complete');
    }
}