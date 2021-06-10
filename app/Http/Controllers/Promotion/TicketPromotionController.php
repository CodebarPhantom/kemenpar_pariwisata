<?php

namespace App\Http\Controllers\Promotion;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use App\Http\Controllers\Controller;
use App\Models\Promotion\TicketPromotion;
use App\Models\Tourism\TourismInfo;
use RealRashid\SweetAlert\Facades\Alert;
use Exception, Laratrust, DataTables;

class TicketPromotionController extends Controller
{
    public function index()
    {
        if (!Laratrust::isAbleTo('view-ticket-promotion')) {
            return abort(404);
        }
        return view('promotion.ticket.index');
    }

    public function data()
    {
        if (!Laratrust::isAbleTo('view-ticket-promotion')) {
            return abort(404);
        }

        $ticketPromotions = TicketPromotion::select(
            'ticket_promotions.id',
            'ticket_promotions.tourism_info_id',
            'ticket_promotions.name',
            'ticket_promotions.start_date',
            'ticket_promotions.end_date',
            'ticket_promotions.disc_percentage',
            'ticket_promotions.status',
            'ti.name as tourism_name'
        )
            ->leftJoin('tourism_infos as ti', 'ti.id', '=', 'ticket_promotions.tourism_info_id')
            ->orderBy('ticket_promotions.end_date', 'DESC');

        if (!Laratrust::hasRole('superadmin')) {
            $ticketPromotions = $ticketPromotions->where('tourism_info_id', auth()->user()->tourism_info_id);
        }

        return DataTables::of($ticketPromotions)
            ->editColumn('disc_percentage', function ($ticketPromotion) {
                return $ticketPromotion->disc_percentage . '%';
            })
            ->editColumn('start_date', function ($ticketPromotion) {
                return $ticketPromotion->start_date
                    ? with(new Carbon($ticketPromotion->start_date))->translatedFormat('D, d-m-Y H:i')
                    : '-';
            })
            ->editColumn('end_date', function ($ticketPromotion) {
                return $ticketPromotion->end_date
                    ? with(new Carbon($ticketPromotion->end_date))->translatedFormat('D, d-m-Y H:i')
                    : '-';
            })

            ->editColumn('status', function ($ticketPromotion) {
                if (date('Y-m-d H:i') < $ticketPromotion->end_date) {
                    $text = 'Masih Berlaku';
                    $color = 'success';
                } else {
                    $text = 'Kadaluarsa';
                    $color = 'danger';
                }

                return '<span class="badge bg-' . $color . '">' . $text . '</span>';
            })

            ->editColumn('action', function ($ticketPromotion) {
                $show =
                    '<a href="' .
                    route('ticket-promotion.show', $ticketPromotion->id) .
                    '" class="btn btn-info btn-flat btn-xs" title="' .
                    Lang::get('Show') .
                    '"><i class="fa fa-eye fa-sm"></i></a>';
                $edit =
                    '<a href="' .
                    route('ticket-promotion.edit', $ticketPromotion->id) .
                    '" class="btn btn-danger btn-flat btn-xs" title="' .
                    Lang::get('Edit') .
                    '"><i class="fa fa-pencil-alt fa-sm"></i></a>';
                return $show . $edit;
            })

            ->filterColumn('start_date', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(ticket_promotions.start_date,'%d-%m-%Y %H:%i') like ?", ["%$keyword%"]);
            })
            ->filterColumn('end_date', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(ticket_promotions.end_date,'%d-%m-%Y %H:%i') like ?", ["%$keyword%"]);
            })
            ->filterColumn('disc_percentage', function ($query, $keyword) {
                $query->whereRaw("CONCAT(ticket_promotions.disc_percentage,'%') like ?", ["%$keyword%"]);
            })
            ->filterColumn('tourism_name', function ($query, $keyword) {
                $query->whereRaw('ti.name like ?', ["%$keyword%"]);
            })

            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function create()
    {
        if (!Laratrust::isAbleTo('view-ticket-promotion')) {
            return abort(404);
        }
        return view('promotion.ticket.create');
    }

    public function store(Request $request)
    {
        if (!Laratrust::isAbleTo('view-ticket-promotion')) {
            return abort(404);
        }

        $this->validate($request, [
            'promotion_name' => 'required',
            'tourism_place' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'percentage' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $ticketPromotion = new TicketPromotion();

            $ticketPromotion->name = $request->promotion_name;
            $ticketPromotion->tourism_info_id = $request->tourism_place;
            $ticketPromotion->start_date = Carbon::parse($request->start_date);
            $ticketPromotion->end_date = Carbon::parse($request->end_date);
            $ticketPromotion->disc_percentage = $request->percentage;
            $ticketPromotion->save();
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return abort(500);
        }
        DB::commit();
        $this->userLog('Membuat Promosi Baru ' . $request->promotion_name);
        Alert::alert('Success', 'Promosi Baru Telah dibuat', 'success');
        return redirect()->route('ticket-promotion.index');
    }

    public function show($id)
    {
        $this->checkPermission($id);

        $ticketPromotion = TicketPromotion::select(
            'ticket_promotions.id',
            'ticket_promotions.tourism_info_id',
            'ticket_promotions.name',
            'ticket_promotions.start_date',
            'ticket_promotions.end_date',
            'ticket_promotions.disc_percentage',
            'ticket_promotions.status',
            'ti.name as tourism_name'
        )
            ->leftJoin('tourism_infos as ti', 'ti.id', '=', 'ticket_promotions.tourism_info_id')
            ->where('ticket_promotions.id', $id)
            ->first();
        return view('promotion.ticket.show', compact('ticketPromotion'));
    }

    public function edit($id)
    {
        $this->checkPermission($id);

        $ticketPromotion = TicketPromotion::select(
            'ticket_promotions.id',
            'ticket_promotions.tourism_info_id',
            'ticket_promotions.name',
            'ticket_promotions.start_date',
            'ticket_promotions.end_date',
            'ticket_promotions.disc_percentage',
            'ticket_promotions.status',
            'ti.name as tourism_name',
            'ti.code as tourism_code',
            'ti.price as tourism_price'
        )
            ->leftJoin('tourism_infos as ti', 'ti.id', '=', 'ticket_promotions.tourism_info_id')
            ->where('ticket_promotions.id', $id)
            ->first();

        return view('promotion.ticket.edit', compact('ticketPromotion'));
    }

    public function update($id, Request $request)
    {
        $this->checkPermission($id);

        $this->validate($request, [
            'promotion_name' => 'required',
            'tourism_place' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'percentage' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $ticketPromotion = TicketPromotion::findOrFail($id);
            $ticketPromotion->name = $request->promotion_name;
            $ticketPromotion->tourism_info_id = $request->tourism_place;
            $ticketPromotion->start_date = Carbon::parse($request->start_date);
            $ticketPromotion->end_date = Carbon::parse($request->end_date);
            $ticketPromotion->disc_percentage = $request->percentage;
            $ticketPromotion->save();
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return abort(500);
        }
        DB::commit();
        $this->userLog('Mengubah Promosi ' . $request->promotion_name);
        Alert::alert('Success', 'Promosi ' . $request->promotion_name . ' Telah di Ubah', 'info');
        return redirect()->route('ticket-promotion.index');
    }

    public function dataTourisms(Request $request)
    {
        $search = $request->search;
        if ($search == '') {
            $tourismInfos = TourismInfo::select('id', 'name', 'price', 'code')->paginate(25);
        } else {
            $tourismInfos = TourismInfo::select('id', 'name', 'price', 'code')
                ->where('name', 'like', '%' . $search . '%')
                ->where('is_active', 1)
                ->paginate(25);
        }

        $response = [];
        foreach ($tourismInfos as $tourismInfo) {
            $response[] = [
                'id' => $tourismInfo->id,
                'text' =>
                    $tourismInfo->code . ' - ' . $tourismInfo->name . ' (' . number_format($tourismInfo->price) . ')',
            ];
        }

        echo json_encode($response);
        exit();
    }

    private function checkPermission($id)
    {
        if (!Laratrust::hasRole('superadmin')) {
            if (
                !TicketPromotion::where('id', $id)
                    ->with('tourism_info')
                    ->first() ||
                TicketPromotion::where('id', $id)
                    ->with('tourism_info')
                    ->first()->tourism_info_id != auth()->user()->tourism_info_id
            ) {
                return abort(
                    config('laratrust.middleware.handlers.abort.code'),
                    config('laratrust.middleware.handlers.abort.message')
                );
            }
        }
    }
}
