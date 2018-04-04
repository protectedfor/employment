<?php

namespace App\Http\Controllers;

use App\Models\BillingLog;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Resumes\City;
use App\Models\Vacancies\Scope;
use App\Models\Vars;
use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class CompaniesController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
	    $scopes = Scope::activeOrder()->get();
	    $cities = City::activeOrder()->get();
	    $companies = Company::with('user', 'user.vacancies')->activated()->searched($request)->paginate(env('CONFIG_PAGINATE', 1));
	    return view('companies.index', compact('scopes', 'cities', 'companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
	    $company = Company::whereId($id)->first();
	    $vacancies = $company->user->vacancies()->ModeratedFixed()->paginate(env('CONFIG_PAGINATE', 1));

	    return view('companies.show', compact('company', 'vacancies'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
