<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    public function index(){
        $reservations =  Reservation::all();
        return $reservations;
    }

    public function show ($book_id, $user_id, $start)
    {
        $reservations = Reservation::where('book_id', $book_id)->where('user_id', $user_id)->where('start', $start)->get();
        return $reservations[0];
    }

    public function destroy($book_id, $user_id, $start)
    {
        ReservationController::show($book_id, $user_id, $start)->delete();
    }

    public function store(Request $request)
    {
        $reservation = new Reservation();
        $reservation->book_id = $request->book_id;
        $reservation->user_id = $request->user_id;
        $reservation->start = $request->start;
        $reservation->message = $request->message;
        $reservation->message_date=$request->message_date;
        $reservation->status=$request->status;
        $reservation->save();
    }

    public function update(Request $request, $book_id, $user_id, $start)
    {
        $reservation = Reservation::show($book_id, $user_id, $start);
        $reservation->book_id = $request->book_id;
        $reservation->user_id = $request->user_id;
        $reservation->start = $request->start;
        $reservation->message = $request->message;
        $reservation->message_date=$request->message_date;
        $reservation->status=$request->status;
        $reservation->save();
    }

    public function elojegyzesDB()
    {
        $user = Auth::user();
        $reservations = DB::table('reservations as r')
            ->select('r.user_id', $user->id)
            ->count();
        return $reservations;
    }

    public function older($day)
    {
        $user=Auth::user();

        $reservations = DB::table('reservations as r')
            ->select('r.book_id', 'r.start')
            ->where('r.user_id', '$user_id')
            ->where('r.status', 0)
            ->whereRaw('DATEDIFF(CURRENT_DATE, r.start) > ?', $day)
            ->get();
        return $reservations;
    }
    

    public function d()
    {
        $reservations = DB::table('books as b')
            ->select('author', 'title')
            ->orderBy('author')
            ->get();
        return $reservations;
    }

    public function deleteOldReservs()
    {
        return DB::select(DB::raw("
            delete from reservations
            where status=1
        "));
    }

    public function reservUsers()
    {
        return DB::select(DB::raw("
            select max(u.name), u.email, count(*) as db
            from reservations r, users u
            where r.user_id=u.id 
            group by u.email 
            having db>=1
        "));
    }

    public function hideOlderThan10()
    {
        return DB::select(DB::raw("
            select book_id, user_id, start
            from reservations
            where message is not null 
            and message_date-current_date()>10
        "));
    }

    public function elrejt()
    {
        DB::update(DB::raw("
        update reservations 
        set status=3
        where book_id=(select book_id
                    from reservations
                    where message =0
                    and message_date-current_date()>10)
        and user_id=(select user_id
                    from reservations
                    where message =0
                    and message_date-current_date()>10)
        and start=(select start
                    from reservations
                    where message =0
                    and message_date-current_date()>10)
        "));
/*
        update reservations 
        set status=3
        where book_id=(select book_id
                    from reservations
                    where message =0
                    and datediff(current_date, message_date)>10)
        and user_id=(select user_id
                    from reservations
                    where message =0
                    and datediff(current_date, message_date)>10)
        and start=(select start
                    from reservations
                    where message =0
                    and datediff( current_date, message_date)>10)*/
    }

}
