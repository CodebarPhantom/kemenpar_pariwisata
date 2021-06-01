<?php

namespace App\Http\Controllers\API\Ticket;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Ticket\PrimaryTests;
use App\Models\Ticket\SecondaryTests;
use Exception;

class TicketController extends Controller
{
    public function index()
    {
        $result = [
            'tickets' => PrimaryTests::where('user_id', auth()->user()->id)
                ->with('secondary_tests')
                ->get(),
        ];

        return $result;
    }

    public function show($id)
    {
        $result = [
            'tickets' => PrimaryTests::where('user_id', auth()->user()->id)
                ->where('id', $id)
                ->with('secondary_tests')
                ->get(),
        ];

        return $result;
        // return $id->with('secondary_tests')->get();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'receipt_number' => 'required',
            'quantity' => 'required|min:1',
        ]);

        DB::beginTransaction();
        try {
            $ticket = new PrimaryTests();
            $ticket->user_id = auth()->user()->id;
            $ticket->name = $request->name;
            $ticket->receipt_number = $request->receipt_number;
            $ticket->save();

            foreach ($request->input('quantity', []) as $i => $quantity){
                $ticketsDetail = new SecondaryTests();
                $ticketsDetail->primary_tests_id = $ticket->id;
                $ticketsDetail->quantity = $request->quantity[$i];
                $ticketsDetail->save();
            }
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return abort(500);
        }
        DB::commit();

        $result = [
            'tickets' => PrimaryTests::where('user_id', auth()->user()->id)
                ->where('id', $ticket->id)
                ->with('secondary_tests')
                ->get(),
        ];

        return $result;
    }

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
