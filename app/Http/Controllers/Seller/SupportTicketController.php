<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Http\Requests\SupportTicket\ConversationRequest;
use App\Http\Requests\SupportTicket\TicketRequest;
use App\Models\SupportTicket;
use App\Models\TicketConversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Mews\Purifier\Facades\Purifier;

class SupportTicketController extends Controller
{
    public function index(Request $request)
    {
        $ticket_id = null;
        if ($request->filled('ticket_id')) {
            $ticket_id = $request->ticket_id;
        }
        $seller = Auth::guard('seller')->user();
        $information['collection'] = SupportTicket::where([['user_id', $seller->id], ['user_type', 'seller']])
            ->when($ticket_id, function ($query) use ($ticket_id) {
                return $query->where('id', $ticket_id);
            })
            ->orderBy('id', 'desc')->get();

        return view('seller.support_ticket.index', $information);
    }

    public function create()
    {
        return view('seller.support_ticket.create');
    }

    public function storeTempFile(Request $request)
    {
        // deleting other temp files
        $tempFiles = glob('assets/file/temp/*');

        if (count($tempFiles) > 0) {
            foreach ($tempFiles as $file) {
                @unlink(public_path($file));
            }
        }

        // storing new file as a temp file
        $file = $request->file('attachment');
        UploadFile::store('./assets/file/temp/', $file);

        return Response::json(['status' => 'success'], 200);
    }

    public function store(TicketRequest $request)
    {
        // deleting temp files
        $tempFiles = glob('assets/file/temp/*');

        if (count($tempFiles) > 0) {
            foreach ($tempFiles as $file) {
                @unlink(public_path($file));
            }
        }

        // storing new file
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = UploadFile::store('./assets/file/ticket-files/', $file);
        }

        $ticket = new SupportTicket();
        $ticket->user_id = Auth::guard('seller')->user()->id;
        $ticket->user_type = 'seller';
        $ticket->admin_id = 1;
        $ticket->ticket_number = uniqid();
        $ticket->subject = $request->subject;
        $ticket->message = Purifier::clean($request->message, 'youtube');
        $ticket->attachment = isset($fileName) ? $fileName : NULL;
        $ticket->save();

        $request->session()->flash('success', 'Ticket submitted successfully.');

        return redirect()->back();
    }

    public function message($id)
    {
        $seller_id = Auth::guard('seller')->user()->id;
        $ticket = SupportTicket::where([['id', $id], ['user_id', $seller_id]])->firstOrFail();
        $queryResult['ticket'] = $ticket;

        $queryResult['conversations'] = $ticket->conversation()->get();
        return view('seller.support_ticket.messages', $queryResult);
    }

    public function ticketreply(ConversationRequest $request, $id)
    {
        // deleting temp files
        $tempFiles = glob('assets/file/temp/*');

        if (count($tempFiles) > 0) {
            foreach ($tempFiles as $file) {
                @unlink(public_path($file));
            }
        }

        // storing new file
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = UploadFile::store('./assets/file/ticket-files/', $file);
        }

        $conversation = new TicketConversation();
        $conversation->ticket_id = $id;
        $conversation->person_id = Auth::guard('seller')->user()->id;
        $conversation->person_type = 'seller';
        $conversation->reply = Purifier::clean($request->reply, 'youtube');
        $conversation->attachment = isset($fileName) ? $fileName : NULL;
        $conversation->save();

        $request->session()->flash('success', 'Reply submitted successfully.');

        return redirect()->back();
    }

    //delete
    public function delete($id)
    {
        //delete all support ticket
        $support_ticket = SupportTicket::where('id', $id)->first();
        if ($support_ticket) {
            //delete conversation 
            $messages = $support_ticket->conversation()->get();
            foreach ($messages as $message) {
                @unlink(public_path('assets/img/support-ticket/' . $message->file));
                $message->delete();
            }
            @unlink(public_path('assets/img/support-ticket/') . $support_ticket->attachment);
            $support_ticket->delete();
        }
        Session::flash('success', 'Support Ticket Deleted Successfully..!');
        return back();
    }

    public function bulk_delete(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $support_ticket = SupportTicket::where('id', $id)->first();
            if ($support_ticket) {
                //delete conversation 
                $messages = $support_ticket->conversation()->get();
                foreach ($messages as $message) {
                    @unlink(public_path('assets/img/support-ticket/' . $message->file));
                    $message->delete();
                }
                @unlink(public_path('assets/img/support-ticket/') . $support_ticket->attachment);
                $support_ticket->delete();
            }
        }
        Session::flash('success', 'Support Tickets are Deleted Successfully..!');
        return response()->json(['status' => 'success'], 200);
    }
}
