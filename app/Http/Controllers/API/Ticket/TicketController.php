<?php

namespace App\Http\Controllers\API\Ticket;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\TicketItems;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Lang;
use Exception, ErrorException;

class TicketController extends Controller
{
    public function __construct(Request $request)
    {
        $this->ticket_fields = '*';
        $this->items_fields = '*';
        $this->categories_fields = '*';

        if (!!$request->ticket_fields) {
            $this->ticket_fields = $request->ticket_fields;

            foreach ($this->ticket_fields as $i => $ticket_fields) {
                $this->ticket_fields[$i] = 'tickets.' . $ticket_fields;
            }
        }

        if (!!$request->items_fields) {
            $this->items_fields = $request->items_fields;

            foreach ($this->items_fields as $i => $items_fields) {
                $this->items_fields[$i] = 'ticket_items.' . $items_fields;
            }

            array_push($this->items_fields, 'ticket_items.id');
        }

        if (!!$request->categories_fields) {
            $this->categories_fields = $request->categories_fields;

            foreach ($this->categories_fields as $i => $categories_fields) {
                $this->categories_fields[$i] = 'tourism_info_categories.' . $categories_fields;
            }

            array_push($this->categories_fields, 'tourism_info_categories.id');
        }
    }

    public function index()
    {
        $ticket_fields = $this->ticket_fields;
        $items_field = $this->items_field;

        try {
            $result = [
                'tickets' => Ticket::select($ticket_fields)
                    ->where('user_id', auth()->user()->id)
                    ->with([
                        'items' => function ($query) use ($items_field) {
                            $query->select($items_field);
                        },
                    ])
                    ->get(),
            ];
        } catch (\Throwable $th) {
            //select all field if fields specified in $ticket_fields or $items_field not exist
            $result = [
                'tickets' => Ticket::where('user_id', auth()->user()->id)
                    ->with('items')
                    ->get(),
            ];
        }

        return response()->json($result, 200);
    }

    public function show($id)
    {
        $ticket_fields = $this->ticket_fields;
        $items_field = $this->items_field;

        try {
            $result = [
                'tickets' => Ticket::select($ticket_fields)
                    ->where('user_id', auth()->user()->id)
                    ->where('id', $id)
                    ->with([
                        'items' => function ($query) use ($items_field) {
                            $query->select($items_field);
                        },
                    ])
                    ->get(),
            ];
        } catch (\Throwable $th) {
            $result = [
                'tickets' => Ticket::where('user_id', auth()->user()->id)
                    ->where('id', $id)
                    ->with('items')
                    ->get(),
            ];
        }

        return response()->json($result, 200);
    }

    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'name' => 'required|alpha|max:255',
                'code' => 'required|alpha_num|unique:tickets,code',
                'tourism_info_id' => 'required|exists:App\Models\Tourism\TourismInfo,id',
                'tourism_info_category_id' => 'required|exists:App\Models\Tourism\TourismInfoCategories,id',
                'price' => 'required|array|min:1',
                'quantity' => 'required|array|min:1',
            ],
            [
                'code.unique' => ['message' => Lang::get('validation.unique'), 'value' => ':input'],
                'tourism_info_id.exists' => ['message' => Lang::get('validation.exists'), 'value' => ':input'],
            ]
        );

        DB::beginTransaction();
        try {
            $ticket = new Ticket();
            $ticket->user_id = auth()->user()->id;
            $ticket->code = $request->name;
            $ticket->name = $request->code;
            $ticket->tourism_info_id = $request->tourism_info_id;
            $ticket->status = 1;
            $ticket->save();

            foreach ($request->tourism_info_category_id as $i => $category_id) {
                $ticketsDetail = new TicketItems();
                $ticketsDetail->ticket_id = $ticket->id;
                $ticketsDetail->tourism_info_category_id = $category_id;
                $ticketsDetail->quantity = $request->quantity[$i];
                $ticketsDetail->price = $request->price[$i];
                $ticketsDetail->save();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        } finally {
            $ticket_fields = $this->ticket_fields;
            $items_fields = $this->items_fields;

            try {
                $result = [
                    'tickets' => Ticket::select($ticket_fields)
                        ->where('user_id', auth()->user()->id)
                        ->where('id', $ticket->id)
                        ->with([
                            'items' => function ($query) use ($items_fields) {
                                $query->select($items_fields);
                            },
                        ])
                        ->get(),
                ];
            } catch (\Throwable $th) {
                $result = [
                    'tickets' => Ticket::where('user_id', auth()->user()->id)
                        ->where('id', $ticket->id)
                        ->with('items')
                        ->with('items.categories')

                        ->get(),
                ];
            }

            return response()->json($result, 200);
        }
    }

    public function storeBulk(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|array|max:255',
            'code' => 'required|array|max:255',
            'tourism_info_id' => 'required|array',
            'tourism_info_category_id' => 'required|array',
            'price' => 'required|array|min:1',
            'quantity' => 'required|array|min:1',
        ]);

        $codes = [];
        $checking_cursor = '';
        $checking_cursor_name = null;

        try {
            foreach ($request->input('name', []) as $i => $name) {
                $checking_cursor = 'code';
                $checking_cursor_name = 'Code';
                if (array_search($request->code[$i], $codes) === 0) {
                    throw new ErrorException($checking_cursor_name . ' tidak boleh sama');
                } else {
                    array_push($codes, $request->code[$i]);
                }
            }
        } catch (ErrorException $e) {
            $errors = (object) [
                $checking_cursor => str_contains($e->getMessage(), 'Undefined offset')
                    ? $checking_cursor_name . ' tidak valid.'
                    : $e->getMessage(),
            ];

            return response()->json(
                [
                    'message' => 'The given data was invalid.',
                    'errors' => $errors,
                ],
                422
            );
        }

        foreach ($request->name as $i => $name) {
            $req = new \Illuminate\Http\Request();
            $req->merge([
                'name' => $name,
                'code' => $request->code[$i],
                'tourism_info_id' => $request->tourism_info_id[$i],
            ]);

            $this->validate(
                $req,
                [
                    'name' => 'required|alpha|max:255',
                    'code' => 'required|alpha_num|unique:tickets,code',
                    'tourism_info_id' => 'required|exists:App\Models\Tourism\TourismInfo,id',
                ],
                [
                    'code.unique' => ['message' => Lang::get('validation.unique'), 'value' => ':input'],
                ]
            );

            foreach ($request->price[$i] as $j => $price) {
                $reqItems = new \Illuminate\Http\Request();
                $reqItems->merge([
                    'price' => $price,
                    'quantity' => $request->quantity[$j][$j],
                    'tourism_info_category_id' => $request->tourism_info_category_id[$i][$j],
                ]);

                $this->validate($reqItems, [
                    'price' => 'required|numeric|min:1',
                    'quantity' => 'required|numeric|min:1',
                    'tourism_info_category_id' => 'required|exists:App\Models\Tourism\TourismInfoCategories,id',
                ]);
            }
        }

        $storedTickets = [];

        DB::beginTransaction();
        try {
            foreach ($request->name as $i => $name) {
                $ticket = new Ticket();
                $ticket->user_id = auth()->user()->id;
                $ticket->name = $name;
                $ticket->code = $request->code[$i];
                $ticket->tourism_info_id = $request->tourism_info_id[$i];
                $ticket->status = 1;
                $ticket->save();

                array_push($storedTickets, $ticket->id);

                foreach ($request->quantity[$i] as $j => $quantity) {
                    $ticketsItems = new TicketItems();
                    $ticketsItems->ticket_id = $ticket->id;
                    $ticketsItems->tourism_info_category_id = $request->tourism_info_category_id[$i][$j];
                    $ticketsItems->price = $request->price[$i][$j];
                    $ticketsItems->quantity = $quantity;
                    $ticketsItems->save();
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            $storedTickets = [];
        } finally {
            $ticket_fields = $this->ticket_fields;
            $items_fields = $this->items_fields;
            $categories_fields = $this->categories_fields;

            try {
                $result = [
                    'tickets' => Ticket::select($ticket_fields)
                        ->where('user_id', auth()->user()->id)
                        ->whereIn('id', $storedTickets)
                        ->with([
                            'items' => function ($query) use ($items_fields, $categories_fields) {
                                $query->select($items_fields);
                            },
                        ])
                        ->with([
                            'items.categories' => function ($query) use ($items_fields, $categories_fields) {
                                $query->select($categories_fields);
                            },
                        ])
                        ->get(),
                ];
            } catch (\Throwable $th) {
                //select all field if fields specified in $primary_fields or $secondary_fields not exist
                $result = [
                    'tickets' => Ticket::where('user_id', auth()->user()->id)
                        ->whereIn('id', $storedTickets)
                        ->with('items.categories')
                        ->get(),
                ];
            }

            return response()->json($result, 200);
        }
    }

    public function update(Request $request, $id)
    {
        dd($id);
    }

    public function destroy($id)
    {
        dd($id);
    }

    public function truncate()
    {
        if (getenv('APP_DEBUG')) {
            Schema::disableForeignKeyConstraints();
            Ticket::truncate();
            TicketItems::truncate();
            Schema::enableForeignKeyConstraints();
        } else {
            abort(404);
        }
    }
}
