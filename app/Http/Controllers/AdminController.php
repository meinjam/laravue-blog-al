<?php

namespace App\Http\Controllers;

use App\Category;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

    public function getcategories() {
        return Category::latest()->get();
    }

    public function upload( Request $request ) {
        $this->validate( $request, [
            'file' => 'required|mimes:jpg,jpeg,png',
        ] );

        $imgName = time() . Str::random( 15 );
        $imgExtension = strtolower( $request->file->getClientOriginalExtension() );
        $imgFullName = "img/category/$imgName.$imgExtension";
        $request->file->move( 'img/category/', "$imgName.$imgExtension" );
        return $imgFullName;
    }

    public function delete_upload( Request $request ) {
        $image = $request->iconImage;
        if ( file_exists( $image ) ) {
            unlink( $image );
        }
    }

    public function addcategory( Request $request ) {
        $this->validate( $request, [
            'categoryName' => 'required',
            'iconImage'    => 'required',
        ] );
        return Category::create( [
            'categoryName' => $request->categoryName,
            'iconImage'    => $request->iconImage,
        ] );
    }

    public function deletecategory( Request $request ) {
        $this->validate( $request, [
            'id' => 'required',
        ] );

        $image = Category::where( 'id', $request->id )->first();
        $delete = Category::where( 'id', $request->id )->delete();
        if ( $delete ) {
            unlink( $image->iconImage );
        }
    }

    public function editcategory( Request $request ) {
        $this->validate( $request, [
            'categoryName' => 'required',
            'iconImage'    => 'required',
            'id'           => 'required',
        ] );
        return Category::where( 'id', $request->id )->update( [
            'categoryName' => $request->categoryName,
            'iconImage'    => $request->iconImage,
        ] );
    }

    public function update( Request $request, $id ) {
        //
    }

    public function destroy( $id ) {
        //
    }
}
