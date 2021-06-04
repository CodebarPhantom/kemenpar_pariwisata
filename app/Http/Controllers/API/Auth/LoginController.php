<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Models\User\UserActivityLog;
use App\Models\Tourism\TourismInfo;
use App\Models\Tourism\TourismInfoCategories;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\TicketItems;
use App\Models\Promotion\TicketPromotion;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $token = $request->user()->createToken($request->token_name);

            return ['token' => $token->plainTextToken];
        } else {
            return response(['token' => null], 403);
        }
    }

    public function show()
    {
        // testing eloquent relationship

        $result = [
            'data' => User::select('users.*')
                ->where('id', auth()->user()->id)
                ->with('tourism_info.categories')
                // ->with('tourism_info.tickets.items')
                // ->with('primary_tests.secondary_tests')
                ->with('tourism_info.promotion')
                // ->with('user_activity_logs')
                ->get(),
        ];

        // $result = [
        //     'data' => UserActivityLog::select('user_activity_logs.*')
        //         ->leftJoin('users as u', 'u.id', '=', 'user_activity_logs.user_id')
        //         ->where('u.id', auth()->user()->id)
        //         ->with('user')
        //         ->get(),
        // ];

        // $result = [
        //     'data' => TourismInfo::select('tourism_infos.*')
        //         ->leftjoin('users as u', 'u.tourism_info_id', '=', 'tourism_infos.id')
        //         ->where('u.id', auth()->user()->id)
        //         ->with('categories')
        //         ->with('tickets.items')
        //         ->with('promotion')
        //         ->get(),
        // ];

        // $result = [
        //     'data' => TourismInfoCategories::select('tourism_info_categories.*')
        //         ->leftjoin('tourism_infos as i', 'i.id', '=', 'tourism_info_categories.tourism_info_id')
        //         ->leftjoin('users as u', 'u.tourism_info_id', '=', 'i.id')
        //         ->where('u.id', auth()->user()->id)
        //         ->with('tourism_info.categories')
        //         ->with('tourism_info.tickets.items')
        //         ->with('tourism_info.promotion')
        //         ->get(),
        // ];

        // $result = [
        //     'data' => Ticket::select('tickets.*')
        //         ->leftjoin('users as u', 'u.id', '=', 'tickets.user_id')
        //         ->where('u.id', auth()->user()->id)
        //         ->with('items')
        //         ->with('tourism_info.categories')
        //         ->with('promotion')
        //         ->get(),
        // ];

        // $result = [
        //     'data' => TicketItems::select('ticket_items.*')
        //     ->leftjoin('tickets as t', 't.id', '=', 'ticket_items.ticket_id')
        //     ->where('t.user_id', auth()->user()->id)
        //     ->with('ticket')
        //     ->with('categories')
        //     ->get(),
        // ];

        // $result = [
        //     'data' => TicketPromotion::select('ticket_promotions.*')
        //     ->leftjoin('tickets as t', 't.ticket_promotion_id', '=', 'ticket_promotions.id')
        //     ->leftjoin('users as u', 'u.id', '=', 't.user_id')
        //     ->where('u.id', auth()->user()->id)
        //     ->with('tourism_info.categories')
        //     ->with('tickets')
        //     ->get(),
        // ];

        return response()->json($result, 200);
    }
}
