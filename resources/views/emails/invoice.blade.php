@component('mail::message')

<!-- Can be coded from start of alignment -->
<table style="width: 100%;border-spacing: 0px;">
<thead>
<tr>
<td colspan="2">
<h2 style="text-align: right;margin-bottom: 10px !important;">INVOICE</h2>
</td>
</tr>
<tr>
<th style="text-align:left;display: inline-table;">
<img style="max-width: 120px;" src="{{public_path($data['logo'])}}" />
</th>
<th style="text-align:right;font-weight:400;">
<h5 style="
font-weight:600;
margin-bottom: 5px;
margin-top: 0px;
">
Invoice No.
</h5>
<span style="font-size: 12px;
margin-bottom: 0.5rem;
display: block;
">{{$data['invoice_no']}}</span>
<h5 style="
margin-bottom: 5px;
margin-top: 0px;
">
Create Date
</h5>
<span style="font-size: 12px;margin-bottom: 0.5rem;display: block;">{{ date('d/m/Y', strtotime($data['create_date']))}}</span>
<h5 style="
font-weight:600;
margin-bottom: 5px;
margin-top: 0px;
">
Due Date
</h5>
<span style="
font-size: 12px;
margin-bottom: 0.5rem;
display: block;
">{{date('d/m/Y', strtotime($data['due_date']))}}</span>
</th>
</tr>
<tr>
<td style="height: 15px;"></td>
</tr>
</thead>
<tbody>
<tr>
<td style="height:15px;width: 50%;">
<h5 style="font-weight:600;margin-bottom: 10px !important;">FROM</h5>
<p style="
margin-top: 0px !important;
margin-bottom: 5px !important;
font-size: 12px;
">
School Name : {{$data['from_name']}}
</p>
<p style="
margin-top: 0px !important;
margin-bottom: 5px !important;
font-size: 12px;
">
phone Number : {{$data['from_phone']}}
</p>
<p style="
margin-top: 0px !important;
margin-bottom: 5px !important;
font-size: 12px;
">
Email : {{$data['from_email']}}
</p>
<p style="
margin-top: 0px !important;
font-size: 12px;
">
Address : {{$data['from_address']}}
</p>
</td>
<td style="
text-align: right;width: 50%;
">
<h5 style="font-weight:600;margin-bottom: 10px !important;">TO</h5>
<p style="
margin-top: 0px !important;
margin-bottom: 5px !important;
font-size: 12px;text-align: right;
">
Name : {{$data['to_name']}}
</p>
<p style="
margin-top: 0px !important;
margin-bottom: 5px !important;
font-size: 12px;text-align: right;
">
phone Number : {{$data['to_phone']}}
</p>
<p style="
margin-top: 0px !important;
margin-bottom: 5px !important;
font-size: 12px;text-align: right;
">
Email : {{$data['to_email']}}
</p>
<p style="
margin-top: 0px !important;
font-size: 12px;text-align: right;
">
Work : {{$data['to_job']}}
</p>
</td>
</tr>
<tr>
<td style="height:15px;"></td>
</tr>
<tr>
<td>
<h5 style="font-weight:600;margin-bottom: 10px !important;">STUDENT INFORMATION</h5>
<p style="
margin-top: 0px !important;
margin-bottom: 5px !important;
font-size: 12px;
">
Name : {{$data['student_name']}}
</p>
<p style="
margin-top: 0px !important;
margin-bottom: 5px !important;
font-size: 12px;
">
Grade : {{$data['student_grade']}}
</p>
</td>
</tr>
<tr>
<td style="height:15px;"></td>
</tr>
</tbody>
</table>
<table style="width: 100%;">
<thead>
<tr>
<td style="
font-weight:600;
padding: 0.5rem;
font-size: 12px;
background: #ececec;
border: 1px solid #ececec;
">
DESCRIPTION
</td>
<td style="
font-weight:600;
padding: 0.5rem;
font-size: 12px;
background: #ececec;
border: 1px solid #ececec;
">
QUANTITY/UNIT
</td>
<td style="
font-weight:600;
padding: 0.5rem;
font-size: 12px;
background: #ececec;
border: 1px solid #ececec;
">
PRICE
</td>
<td style="
font-weight:600;
padding: 0.5rem;
font-size: 12px;
background: #ececec;
border: 1px solid #ececec;
">
TOTAL
</td>
</tr>
</thead>
<tbody>
@foreach ($data['items'] as $item)
<tr>
<td style="
padding: 0.5rem;
font-size: 12px;
background: #fff;
border: 1px solid #ececec;
">
{{$item['description']}}
</td>
<td style="
padding: 0.5rem;
font-size: 12px;
background: #fff;
border: 1px solid #ececec;
">
{{$item['quantity']}}
</td>
<td style="
padding: 0.5rem;
font-size: 12px;
background: #fff;
border: 1px solid #ececec;
">
{{$item['price']}}ks
</td>
<td style="
padding: 0.5rem;
font-size: 12px;
background: #fff;
border: 1px solid #ececec;
">
{{$item['total']}}Ks
</td>
</tr>
@endforeach
</tbody>
<tbody>
<tr>
<td colspan="3" style="
font-weight:600;
padding: 0.5rem;
font-size: 12px;
background: #fff;
border: 1px solid #ececec;
">
SUB TOTAL
</td>
<td style="
padding: 0.5rem;
font-size: 12px;
background: #fff;
border: 1px solid #ececec;
">
{{$data['sub_total']}}Ks
</td>
</tr>
<tr>
<td colspan="3" style="
font-weight:600;
padding: 0.5rem;
font-size: 12px;
background: #fff;
border: 1px solid #ececec;
">
DISCOUNT %
</td>
<td style="
padding: 0.5rem;
font-size: 12px;
background: #fff;
border: 1px solid #ececec;
">
{{$data['discount']}}%
</td>
</tr>
<tr>
<td colspan="3" style="
font-weight:600;
padding: 0.5rem;
font-size: 12px;
background: #fff;
border: 1px solid #ececec;
">
GRAND TOTAL
</td>
<td style="
padding: 0.5rem;
font-size: 12px;
background: #fff;
border: 1px solid #ececec;
">
{{$data['grand_total']}}Ks
</td>
</tr>
<tr>
<td style="height:15px;"></td>
</tr>
</tbody>
</table>
<table style="width: 100%;">
<tbody>
<tr>
<td>
<h5 style="font-weight:600;margin-bottom: 10px !important;">BANK INFORMATION</h5>
<p style="
margin-top: 0px !important;
margin-bottom: 5px !important;
font-size: 12px;
">
Bank Name : {{$data['bank_name']}}
</p>
<p style="
margin-top: 0px !important;
margin-bottom: 5px !important;
font-size: 12px;
">
Account Holder : {{$data['bank_holder_name']}}
</p>
<p style="
margin-top: 0px !important;
margin-bottom: 5px !important;
font-size: 12px;
">
Account Number : {{$data['bank_no']}}
</p>
</td>
<td style="
text-align:right;
">
<div style="text-align:center;">
<img style="max-width: 100px; margin-top:1.5rem;" src="{{public_path($data['signature'])}}" />
<p style="text-align:center;font-size: 12px; margin-bottom:0px;">Signature</p>
</div>
</td>
</tr>
</tbody>
</table>

@endcomponent