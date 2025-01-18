<?php

namespace App\Http\Controllers\API\Dashboard\ContactUs;

use App\Http\Controllers\Controller;
use App\Models\ContactUs\ContactUsMessage;
use App\Services\ContactUs\ContactMessageService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;


class ContactUsMessageController extends Controller
{
    protected $contactUsMessageService;

    public function __construct(ContactMessageService $contactUsMessageService)
    {
        // $this->middleware('auth:api');
        $this->contactUsMessageService = $contactUsMessageService;
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function create(Request $request)
    {
        try {
            DB::beginTransaction();
            $contactUsMessage = $this->contactUsMessageService->create($request->all());
            DB::commit();
            return response()->json([
                'message' => __('messages.success.created')
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }

    public function read(Request $request)
    {
        $message = ContactUsMessage::find($request->contactUsMessageId);

        $message->update([
            'is_read' => Carbon::now()
        ]);

        return response()->json([
            'message' => __('messages.success.updated')
        ], 200);


    }

}
