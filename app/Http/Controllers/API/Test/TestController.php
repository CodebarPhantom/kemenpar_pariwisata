<?php

namespace App\Http\Controllers\API\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Test\PrimaryTests;
use App\Models\Test\SecondaryTests;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Lang;
use Exception, ErrorException;

class TestController extends Controller
{
    public function __construct(Request $request)
    {
        $this->primary_fields = '*';
        $this->secondary_fields = '*';

        if (!!$request->primary_fields) {
            $this->primary_fields = $request->primary_fields;
        }

        if (!!$request->secondary_fields) {
            $this->secondary_fields = $request->secondary_fields;
            array_push($this->secondary_fields, 'primary_tests_id');
        }
    }

    public function index()
    {
        $primary_fields = $this->primary_fields;
        $secondary_fields = $this->secondary_fields;

        try {
            $result = [
                'tickets' => PrimaryTests::select($primary_fields)
                    ->where('user_id', auth()->user()->id)
                    ->with([
                        'secondary_tests' => function ($query) use ($secondary_fields) {
                            $query->select($secondary_fields);
                        },
                    ])
                    ->get(),
            ];
        } catch (\Throwable $th) {
            $result = [
                'tickets' => PrimaryTests::where('user_id', auth()->user()->id)
                    ->with('secondary_tests')
                    ->get(),
            ];
        }

        return response()->json($result, 200);
    }

    public function show($id)
    {
        $primary_fields = $this->primary_fields;
        $secondary_fields = $this->secondary_fields;

        try {
            $result = [
                'tickets' => PrimaryTests::select($primary_fields)
                    ->where('user_id', auth()->user()->id)
                    ->where('id', $id)
                    ->with([
                        'secondary_tests' => function ($query) use ($secondary_fields) {
                            $query->select($secondary_fields);
                        },
                    ])
                    ->get(),
            ];
        } catch (\Throwable $th) {
            $result = [
                'tickets' => PrimaryTests::where('user_id', auth()->user()->id)
                    ->where('id', $id)
                    ->with('secondary_tests')
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
                'name' => 'required|string|max:255',
                'receipt_number' => 'required|string|unique:primary_tests,receipt_number',
                'quantity' => 'required|min:1',
            ],
            [
                'receipt_number.unique' => ['message' => Lang::get('validation.unique'), 'value' => ':input'],
            ]
        );

        DB::beginTransaction();
        try {
            $ticket = new PrimaryTests();
            $ticket->user_id = auth()->user()->id;
            $ticket->name = $request->name;
            $ticket->receipt_number = $request->receipt_number;
            $ticket->status = 1;
            $ticket->save();

            foreach ($request->input('quantity', []) as $i => $quantity) {
                $ticketsDetail = new SecondaryTests();
                $ticketsDetail->primary_tests_id = $ticket->id;
                $ticketsDetail->quantity = $request->quantity[$i];
                $ticketsDetail->save();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        } finally {
            $primary_fields = $this->primary_fields;
            $secondary_fields = $this->secondary_fields;

            try {
                $result = [
                    'tickets' => PrimaryTests::select($primary_fields)
                        ->where('user_id', auth()->user()->id)
                        ->where('id', $ticket->id)
                        ->with([
                            'secondary_tests' => function ($query) use ($secondary_fields) {
                                $query->select($secondary_fields);
                            },
                        ])
                        ->get(),
                ];
            } catch (\Throwable $th) {
                //select all field if fields specified in $primary_fields or $secondary_fields not exist
                $result = [
                    'tickets' => PrimaryTests::where('user_id', auth()->user()->id)
                        ->where('id', $ticket->id)
                        ->with('secondary_tests')
                        ->get(),
                ];
            }

            return response()->json($result, 200);
        }
    }

    public function storeBulk(Request $request)
    {
        $this->validate(
            $request,
            [
                'name' => 'required|array|max:255',
                'receipt_number' => 'required|array|unique:primary_tests,receipt_number',
                'quantity' => 'required|array|min:1',
            ],
            [
                'receipt_number.unique' => ['message' => Lang::get('validation.unique'), 'value' => ':input'],
            ]
        );

        $receipt_numbers = [];
        $checking_cursor = '';
        $checking_cursor_name = null;

        try {
            foreach ($request->input('name', []) as $i => $name) {
                $checking_cursor = 'receipt_number';
                $checking_cursor_name = 'Receipt number';
                if (array_search($request->receipt_number[$i], $receipt_numbers) === 0) {
                    throw new ErrorException($checking_cursor_name . ' tidak boleh sama.');
                } else {
                    array_push($receipt_numbers, $request->receipt_number[$i]);
                }

                $checking_cursor = 'quantity';
                $checking_cursor_name = 'Quantity';
                if (!$request->input('quantity', [])[$i]) {
                    throw new ErrorException($checking_cursor_name . ' tidak valid.');
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
                'receipt_number' => $request->receipt_number[$i],
                'quantity' => $request->quantity[$i],
            ]);

            $this->validate(
                $req,
                [
                    'name' => 'required|max:255',
                    'receipt_number' => 'required|unique:primary_tests,receipt_number',
                    'quantity' => 'required|array|min:1',
                ],
                [
                    'receipt_number.unique' => ['message' => Lang::get('validation.unique'), 'value' => ':input'],
                ]
            );
        }

        $storedTickets = [];

        DB::beginTransaction();
        try {
            foreach ($request->input('name', []) as $i => $name) {
                $ticket = new PrimaryTests();
                $ticket->user_id = auth()->user()->id;
                $ticket->name = $name;
                $ticket->receipt_number = $request->receipt_number[$i];
                $ticket->status = 1;
                $ticket->save();

                array_push($storedTickets, $ticket->id);

                foreach ($request->input('quantity', [])[$i] as $quantity) {
                    $ticketsDetail = new SecondaryTests();
                    $ticketsDetail->primary_tests_id = $ticket->id;
                    $ticketsDetail->quantity = $quantity;
                    $ticketsDetail->save();
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $storedTickets = [];
        } finally {
            $primary_fields = $this->primary_fields;
            $secondary_fields = $this->secondary_fields;

            try {
                $result = [
                    'tickets' => PrimaryTests::select($primary_fields)
                        ->where('user_id', auth()->user()->id)
                        ->whereIn('id', $storedTickets)
                        ->with([
                            'secondary_tests' => function ($query) use ($secondary_fields) {
                                $query->select($secondary_fields);
                            },
                        ])
                        ->get(),
                ];
            } catch (\Throwable $th) {
                //select all field if fields specified in $primary_fields or $secondary_fields not exist
                $result = [
                    'tickets' => PrimaryTests::where('user_id', auth()->user()->id)
                        ->whereIn('id', $storedTickets)
                        ->with('secondary_tests')
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
        Schema::disableForeignKeyConstraints();
        PrimaryTests::truncate();
        SecondaryTests::truncate();
        Schema::enableForeignKeyConstraints();
    }

    public function messages()
    {
        return [
            'title.required' => 'A title is required',
            'body.required' => 'A message is required',
        ];
    }
}
