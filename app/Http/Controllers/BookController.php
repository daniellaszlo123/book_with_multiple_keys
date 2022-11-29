<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    public function index(){
        $books =  Book::all();
        return $books;
    }
    
    public function show($id)
    {
        $book = Book::find($id);
        return $book;
    }
    public function destroy($id)
    {
        Book::find($id)->delete();
    }
    public function store(Request $request)
    {
        $Book = new Book();
        $Book->author = $request->author;
        $Book->title = $request->title;
        $Book->save();
    }

    public function update(Request $request, $id)
    {
        $Book = Book::find($id);
        $Book->author = $request->author;
        $Book->title = $request->title;
    }

    public function bookCopies($title)
    {	
        $copies = Book::with('copy_c')->where('title','=', $title)->get();
        return $copies;
    }

    public function szerzokentCsopABC()
    {
        $books = DB::table('books as b')
            ->select('author', 'title')
            ->orderBy('author')
            ->get();
        return $books;
    }

    public function moreThan1()
    {
        $books = DB::table('books as b')
            ->selectRaw('b.author, count(*)')
            ->groupBy('b.author')
            ->having('count(*)>=2')
            ->get();
        return $books;
    }

    public function startsWithB()
    {
        $books = DB::table('books as b')
            ->select('b.author')
            ->whereRaw('b.author like "B%"')
            ->get();
        return $books;
    }
    
    public function startsWithBArgument($text)
    {
        $books = DB::table('books as b')
            ->select('b.author')
            ->where('b.author', 'like', $text.'%')
            //->whereRaw('b.author like "${text}%"')
            ->get();
        return $books;
    }

}
