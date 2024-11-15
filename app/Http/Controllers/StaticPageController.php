<?php
/**
 * Assessment Title: Portfolio Part 3
 * Cluster: SaaS: Part 1 -- Front End Development
 * Qualification: ICT50220 Diploma of Information Technology (Advanced Programming)
 * Name: Andre Velevski
 * Student ID: 2004240
 * Year/Semester: 2024/S2
 *
 * Static page controller for handling basic site pages
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use App\Models\Joke;

class StaticPageController extends Controller
{
    /**
     * Display the home page
     *
     * @return View
     */
    public function home(): view
    {
        $totalMembers = DB::table('users')->count();
        $totalJokes = DB::table('jokes')->count();
        $randomJoke = Joke::with('author')
            ->inRandomOrder()
            ->first();
        return view('static.home', [
            'total_members' => $totalMembers,
            'total_jokes' => $totalJokes,
            'random_joke' => $randomJoke]);
    }

    /**
     * Display the about page
     *
     * @return View
     */
    public function about(): view
    {
        return view('static.about');
    }

    /**
     * Display the contact page
     *
     * @return View
     */
    public function contact(): view
    {
        return view('static.contact');
    }
}
