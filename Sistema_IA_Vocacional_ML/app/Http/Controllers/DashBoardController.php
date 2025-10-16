<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use App\Repositories\TestRepository;
use App\Repositories\CareerRepository;

class DashboardController extends Controller
{
    protected $testRepository;
    protected $careerRepository;

    public function __construct(TestRepository $testRepository, CareerRepository $careerRepository)
    {
        $this->middleware('auth');
        $this->testRepository = $testRepository;
        $this->careerRepository = $careerRepository;
    }

    public function index()
    {
        $user = Auth::user();
        
        $completedTests = $this->testRepository->getUserCompletedTests($user->id);
        $totalTests = $this->testRepository->getActiveTests();
        
        return view('dashboard.index', compact('user', 'completedTests', 'totalTests'));
    }

    public function tests()
    {
        return view('dashboard.tests');
    }

    public function careers()
    {
        $careers = $this->careerRepository->getAllCareers();
        $faculties = $this->careerRepository->getAllFaculties();
        
        return view('dashboard.careers', compact('careers', 'faculties'));
    }

    public function recommendations()
    {
        $latestResult = $this->testRepository->getUserLatestResult(Auth::id());
        
        return view('dashboard.recommendations', compact('latestResult'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('dashboard.profile', compact('user'));
    }
}
