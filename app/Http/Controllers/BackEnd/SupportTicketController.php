<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Http\Requests\SupportTicket\ConversationRequest;
use App\Models\Admin;
use App\Models\BasicSettings\Basic;
use App\Models\SupportTicket;
use App\Models\TicketConversation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;

class SupportTicketController extends Controller
{
  public function settings()
  {
    $status = Basic::query()->pluck('support_ticket_status')->first();

    return view('backend.support-ticket.settings', compact('status'));
  }

  public function updateSettings(Request $request)
  {
    $request->validate([
      'support_ticket_status' => 'required|numeric'
    ]);

    Basic::query()->updateOrCreate(
      ['uniqid' => 12345],
      ['support_ticket_status' => $request->support_ticket_status]
    );

    $request->session()->flash('success', 'Settings updated successfully.');

    return redirect()->back();
  }


  public function tickets(Request $request)
  {
    $ticketNumber = $ticketStatus = null;

    if ($request->filled('ticket_no')) {
      $ticketNumber = $request['ticket_no'];
    }
    if ($request->filled('ticket_status')) {
      $ticketStatus = $request['ticket_status'];
    }

    $authAdmin = Auth::guard('admin')->user();

    $queryResult['tickets'] = SupportTicket::when($ticketNumber, function (Builder $query, $ticketNumber) {
      return $query->where('id', 'like', '%' . $ticketNumber . '%');
    })
      ->when($ticketStatus, function (Builder $query, $ticketStatus) {
        return $query->where('status', '=', $ticketStatus);
      })
      ->orderByDesc('id')
      ->paginate(10);

    $queryResult['admins'] = Admin::query()->where('status', '=', 1)->get();

    return view('backend.support-ticket.tickets', $queryResult);
  }

  public function assignAdmin(Request $request, $id)
  {
    $rule = [
      'admin_id' => 'required'
    ];

    $message = [
      'admin_id.required' => 'Please, select an admin.'
    ];

    $validator = Validator::make($request->all(), $rule, $message);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    $ticket = SupportTicket::query()->find($id);

    $ticket->update([
      'admin_id' => $request->admin_id
    ]);

    $request->session()->flash('success', 'Admin assigned successfully.');

    return Response::json(['status' => 'success', 200]);
  }

  public function unassign_stuff($id)
  {
    SupportTicket::where('id', $id)->update([
      'admin_id' => null,
    ]);

    Session::flash('success', 'Unassign stuff successfully!');
    return back();
  }

  public function conversation($id)
  {
    $ticket = SupportTicket::query()->findOrFail($id);
    $queryResult['ticket'] = $ticket;

    $queryResult['conversations'] = $ticket->conversation()->get();

    return view('backend.support-ticket.conversation', $queryResult);
  }

  public function close($id)
  {
    $ticket = SupportTicket::query()->find($id);

    $ticket->update([
      'status' => 'closed'
    ]);

    return redirect()->back()->with('success', 'Ticket has been closed!');
  }

  public function storeTempFile(Request $request)
  {
    $file = $request->file('attachment');
    $fileExtension = $file->getClientOriginalExtension();

    // convert mb to kb
    $maxSize = 20 * 1024;
    $request->validate([
      'attachment' => [
        function ($attribute, $value, $fail) use ($fileExtension) {
          if (strcmp('zip', $fileExtension) != 0) {
            $fail('The ' . $attribute . ' must be a file of type: zip.');
          }
        },
        'max:' . $maxSize
      ]
    ]);

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

  public function reply(ConversationRequest $request, $id)
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

    // storing data in db
    $conversation = new TicketConversation();
    $conversation->ticket_id = $id;
    $conversation->person_id = Auth::guard('admin')->user()->id;
    $conversation->person_type = 'admin';
    $conversation->reply = Purifier::clean($request->reply, 'youtube');
    $conversation->attachment = isset($fileName) ? $fileName : NULL;
    $conversation->save();

    // changing ticket status
    $ticket = $conversation->ticket()->first();

    $ticket->update([
      'status' => 'open'
    ]);

    $request->session()->flash('success', 'Reply submitted successfully.');

    return redirect()->back();
  }

  public function destroy($id)
  {
    $this->deleteTicket($id);

    return redirect()->back()->with('success', 'Ticket has been deleted!');
  }

  public function bulkDestroy(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $this->deleteTicket($id);
    }

    $request->session()->flash('success', 'Tickets has deleted!');

    return Response::json(['status' => 'success'], 200);
  }

  public function deleteTicket($id)
  {
    $ticket = SupportTicket::query()->find($id);

    // delete ticket conversations
    $conversations = $ticket->conversation()->get();

    if (count($conversations) > 0) {
      foreach ($conversations as $conversation) {
        @unlink(public_path('assets/file/ticket-files/' . $conversation->attachment));

        $conversation->delete();
      }
    }
    // delete ticket
    @unlink(public_path('assets/file/ticket-files/' . $ticket->attachment));

    $ticket->delete();
  }
}
