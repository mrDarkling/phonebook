<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PhoneBookResource;
use App\Http\Requests\PhoneBookRequest;
use App\Model\PhoneBook;
use Illuminate\Http\Request;


class PhoneBookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        return PhoneBookResource::collection(PhoneBook::get());
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(PhoneBookRequest $request)
    {
        $data = $request->all();
        $request = new PhoneBook();
        $request->fill($data);
        $request->save();
    }

    /**
     * Display the specified resource.
     *
     */
    public function show(PhoneBook $phoneBook)
    {
        return new PhoneBookResource($phoneBook);
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function edit($phoneBook)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(PhoneBookRequest $request,PhoneBook $phoneBook):bool
    {
        $phoneBook->fill($request->all());
        $phoneBook->save();
        return true;
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy(PhoneBook $phoneBook)
    {
        $phoneBook->delete();
    }
}
