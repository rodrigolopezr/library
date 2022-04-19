<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Books;
use Illuminate\Http\Request;
use DateTime;

class BooksController extends Controller
{
    public function get(){
    	try
    	{
			$books = DB::table('books')
	            ->join('categories', 'categories.id', '=', 'books.CategoryId')
	            ->join('users', 'users.id', '=', 'books.UserId')
	            ->select('books.*')
	            ->selectraw('categories.name as CategoryName, users.name as UserName')
	            ->get();

	        return response()->json(['success' => $books], 200);  
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }


    }

    public function new(Request $request)
    {
        try{
            $this->validate($request, [
            'Name' => 'required | string',
            'Author' => 'required | string',
            'CategoryId' => 'required | integer',
            'UserId' => 'required | integer'
            ]);

            $cliente = Books::create([
                'Name' => $request->Name,
                'Author' => $request->Author,
                'CategoryId' => $request->CategoryId,
                'UserId' => $request->UserId,
                'PublicationDate' => new DateTime(),
                'Status' => 'ready'
            ]);
            

            return response()->json(['success' => $cliente], 200);  
            
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request){    	
    	try{
                $this->validate($request, [
                'Name' => 'required',
                'Author' => 'required',
                'Status' => 'required'
                ]);

                $books = Books::where('id' , $request->bookId)->update([
                            'Name' => $request->Name,
                            'Author' => $request->Author,
                            'Status' => $request->Status                            
                        ]);
            

            return response()->json(['success' => $books], 200);
    	}catch(\Exception $e){
    		return response()->json(['error' => $e->getMessage()], 500);
    	}
    }

    public function delete(Request $request){       
        try{
                $this->validate($request, [              
                'bookId' => 'required'
                ]);

                $books = DB::table('books')->where('id', $request->bookId)->delete();
            

            return response()->json(['success' => $books], 200);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
