<?php

namespace Ares\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class SiteController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index()
    {
        return Auth::check()
            ? redirect()->route('dashboard')
            : view('index');
    }

    public function test()
    {
        return view('test');
    }

    public function faq()
    {
        return view('faq');
    }

    public function dashboard()
    {
        $campaigns = getCampaignsForUser(1);
        $inactiveCampaigns = getCampaignsForUser(0);
        return Auth::check() ? view('dashboard', ['campaigns' => $campaigns, 'inactiveCampaigns' => $inactiveCampaigns]) : view('index');
    }
    
    public function campaign()
    {
        return Auth::check() ? view('campaign') : view('index');
    }
    public function upcoming()
    {
        return Auth::check() ? view('upcoming') : view('index');
    }

}
