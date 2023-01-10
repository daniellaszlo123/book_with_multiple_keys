<?php

namespace App\Http\Controllers;

use App\Models\Copy;
use App\Models\Lending;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LendingController extends Controller
{
    //
    public function index(){
        $lendings =  Lending::all();
        return $lendings;
    }

    public function show ($user_id, $copy_id, $start)
    {
        $lending = Lending::where('user_id', $user_id)->where('copy_id', $copy_id)->where('start', $start)->get();
        return $lending[0];
    }
    public function destroy($user_id, $copy_id, $start)
    {
        LendingController::show($user_id, $copy_id, $start)->delete();
    }

    public function store(Request $request)
    {
        $lending = new Lending();
        $lending->user_id = $request->user_id;
        $lending->copy_id = $request->copy_id;
        $lending->start = $request->start;
        $lending->save();
    }

    public function update(Request $request, $user_id, $copy_id, $start)
    {
        $lending = LendingController::show($user_id, $copy_id, $start);
        $lending->user_id = $request->user_id;
        $lending->copy_id = $request->copy_id;
        $lending->start = $request->start;
        $lending->save();
    }

    public function userLendingsList()
    {
        $user = Auth::user();	//bejelentkezett felhasználó
        $lendings = Lending::with('user_c')->where('user_id','=', $user->id)->get();
        return $lendings;
    }

    public function userLendingsCount()
    {
        $user = Auth::user();	//bejelentkezett felhasználó
        $lendings = Lending::with('user_c')->where('user_id','=', $user->id)->distinct('copy_id')->count();
        return $lendings;
    }

    public function userLendingsCountWithoutDistinct()
    {
        $user = Auth::user();	//bejelentkezett felhasználó
        $lendings = Lending::with('user_c')->where('user_id','=', $user->id)->count();
        return $lendings;
    }

    public function inStorage()
    {
        $copies = DB::table('copies as c')
        ->where('c.status','=', '0')
        ->count();

        return $copies;
    }


    public function bringBack($copy_id, $start)
    {
        $user=Auth::user();
        $lending=LendingController::show($user->id, $copy_id, $start);
        $lending->end=date(now());
        $lending->save();
        DB::table('copies')    
        ->where('copy_id', $copy_id)
        ->update(['status'=>0]);
    }
    

    //view-k:
    public function newView()
    {
        //új rekord(ok) rögzítése
        $users = User::all();
        $copies = Copy::all();
        return view('lending.new', ['users' => $users, 'copies' => $copies]);
    }
//Dániel² vagyok
    public function kolcsParam($db)
    {
        $user=Auth::user();
        $id=$user->id;
        $kolcsonok=DB::select(
            DB::raw("
                select copy_id, count(copy_id) as db
                from lendings
                where user_id=${id}
                group by copy_id
                having count(copy_id)>=${db}
            ")
        );
        return $kolcsonok;
    }

    public function reserved($book_id)
    {
        return DB::select(DB::raw("select count(*) as foglalt
                                 from reservations
                                 where message=0 and status=0
                                 and book_id=${book_id}"));
    }
}
