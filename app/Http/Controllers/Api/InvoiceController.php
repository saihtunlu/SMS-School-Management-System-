<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Invoice;
use App\InvoiceItem;
use App\InvoiceDetail;
use App\InvoiceItemDetail;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendInvoice;

class InvoiceController extends Controller
{


    public function index()
    {
        Invoice::where([['due_date', '<', now()], ['status', '!=', 'Paid']])->update(['status' => 'Post_Due']);
        $Invoices = Invoice::with('items')->get();

        return response()->json($Invoices);
    }
    public function Getdefault()
    {
        $Invoices = InvoiceDetail::first();

        return response()->json($Invoices);
    }
    public function updateStatus(Request $request)
    {
        $invoice = Invoice::find($request->id);
        $invoice->status = $request->status;
        $invoice->save();
        return response()->json(['success']);
    }
    public function GetInvoiceItems()
    {
        $items = InvoiceItemDetail::get();

        return response()->json($items);
    }
    public function defaultDetail(Request $request)
    {
        //Set Variables
        $invoice = json_decode($request->invoice);
        $logo = $request->logo;
        $signature = $request->sign;

        if ($logo) {
            $Logo_Name = time() . '-Logo.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('/images/uploads/'), $Logo_Name);
            $Logo = '/images/uploads/' . $Logo_Name;
        } else {
            $Logo = $invoice->logo;
        }
        if ($signature) {
            $Sign_Name = time() . '-signature.' . $signature->getClientOriginalExtension();
            $signature->move(public_path('/images/uploads/'), $Sign_Name);
            $Sign = '/images/uploads/' . $Sign_Name;
        } else {
            $Sign = $invoice->sign;
        }
        if (!empty($invoice->id)) {
            InvoiceDetail::where('id', $invoice->id)->delete();
        }
        $Invoice = new InvoiceDetail();
        $Invoice->name = $invoice->name;
        $Invoice->email = $invoice->email;
        $Invoice->phone = $invoice->phone;
        $Invoice->address = $invoice->address;
        $Invoice->bank_name = $invoice->bank_name;
        $Invoice->account_holder = $invoice->account_holder;
        $Invoice->account_number = $invoice->account_number;
        $Invoice->logo = $Logo;
        $Invoice->sign = $Sign;
        $Invoice->save();
        return response()->json(['success' => $Invoice]);
    }

    public function store(Request $request)
    {
        //Set Variables
        $invoice = json_decode($request->invoice);
        $logo = $request->logo;
        $signature = $request->sign;

        if ($logo) {
            $Logo_Name = time() . '-Logo.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('/images/uploads/'), $Logo_Name);
            $Logo = '/images/uploads/' . $Logo_Name;
        } else {
            $Logo = $invoice->logo;
        }
        if ($signature) {
            $Sign_Name = time() . '-signature.' . $signature->getClientOriginalExtension();
            $signature->move(public_path('/images/uploads/'), $Sign_Name);
            $Sign = '/images/uploads/' . $Sign_Name;
        } else {
            $Sign = $invoice->signature;
        }
        $Invoice = new Invoice();
        $Invoice->create_date = $invoice->create_date;
        $Invoice->due_date = $invoice->due_date;
        $Invoice->from_name = $invoice->from_name;
        $Invoice->from_phone = $invoice->from_phone;
        $Invoice->from_address = $invoice->from_address;
        $Invoice->from_email = $invoice->from_email;
        $Invoice->to_email = $invoice->to_email;
        $Invoice->to_name = $invoice->to_name;
        $Invoice->to_phone = $invoice->to_phone;
        $Invoice->to_job = $invoice->to_job;
        $Invoice->student_name = $invoice->student_name;
        $Invoice->student_grade = $invoice->student_grade;
        $Invoice->bank_name = $invoice->bank_name;
        $Invoice->bank_holder_name = $invoice->bank_holder_name;
        $Invoice->bank_no = $invoice->bank_no;
        $Invoice->sub_total = $invoice->sub_total;
        $Invoice->discount = $invoice->discount;
        $Invoice->grand_total = $invoice->grand_total;
        $Invoice->primary_total = $invoice->primary_total;
        $Invoice->logo = $Logo;
        $Invoice->signature = $Sign;
        $Invoice->save();

        $invoice_id = $Invoice->id;
        $invoice_ = Invoice::find($invoice_id);
        $invoice_->invoice_no = '#' . str_pad($Invoice->id, 4, '0', STR_PAD_LEFT);
        $invoice_->save();

        foreach ($invoice->items as $data) {
            $Item = new InvoiceItem();
            $Item->description = $data->description;
            $Item->quantity = $data->quantity;
            $Item->price = $data->price;
            $Item->total = $data->total;
            $Item->invoice_id = $invoice_id;
            $Item->save();
        }
        return response()->json($Invoice->id);
    }
    public function SetInvoiceItems(Request $request)
    {

        foreach ($request->items as $data) {
            if (!empty($data['id'])) {
                InvoiceItemDetail::where('id', $data['id'])->delete();
            }
            $Item = new InvoiceItemDetail();
            $Item->description = $data['description'];
            $Item->price = $data['price'];
            $Item->primary_price = $data['primary_price'];
            $Item->save();
        }
        return response()->json(['success']);
    }
    public function SendInvoice(Request $request)
    {


        $data = $request->data;
        $send_email = $request->send_email;
        $id = $data['id'];
        $invoice_ = Invoice::find($id);
        $data['invoice_no'] = $invoice_->invoice_no;

        $check = Invoice::find($id);
        if ($send_email === $check->to_email) {
            if ($check->sent === 0) {
                $check->sent = 1;
                $check->save();
            }
        }
        Mail::to($send_email)->send(new SendInvoice($data));
        return response()->json(['success']);
    }
}
