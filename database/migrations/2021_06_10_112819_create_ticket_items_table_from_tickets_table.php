<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\TicketItems;

class CreateTicketItemsTableFromTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tickets = Ticket::select('tickets.*')
            ->where('price', '>', 0)
            ->with('tourism_info.categories')
            ->get();

        DB::beginTransaction();
        try {
            foreach ($tickets as $ticket) {
                $ticketItem = new TicketItems();
                $ticketItem->ticket_id = $ticket->id;
                $ticketItem->tourism_info_category_id = $ticket['tourism_info']['categories'][0]->id;
                $ticketItem->quantity = 1;
                $ticketItem->price = $ticket->price;
                $ticketItem->created_at = $ticket->created_at;
                $ticketItem->updated_at = $ticket->updated_at;
                $ticketItem->save();
                $ticket->price = 0;
                $ticket->save();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return abort($e);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //TODO: reverse migration
    }
}
