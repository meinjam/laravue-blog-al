<?php

namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Http\Request;

class AdminController extends Controller {

    public function gettags() {
        return Tag::orderBy( 'id', 'desc' )->get();
    }

    public function addtag( Request $request ) {
        $this->validate( $request, [
            'tagName' => 'required',
        ] );
        return Tag::create( [
            'tagName' => $request->tagName,
        ] );
    }

    public function edittag( Request $request ) {
        $this->validate( $request, [
            'tagName' => 'required',
            'id'      => 'required',
        ] );
        return Tag::where( 'id', $request->id )->update( [
            'tagName' => $request->tagName,
        ] );
    }

    public function deletetag( Request $request ) {
        $this->validate( $request, [
            'id' => 'required',
        ] );
        return Tag::where( 'id', $request->id )->delete();
    }

    public function index() {
        //
    }

    public function create() {
        //
    }

    public function store( Request $request ) {
        //
    }

    public function show( $id ) {
        //
    }

    public function edit( $id ) {
        //
    }

    public function update( Request $request, $id ) {
        //
    }

    public function destroy( $id ) {
        //
    }
}
