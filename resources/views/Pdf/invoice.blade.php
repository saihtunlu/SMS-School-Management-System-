<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
</head>


<body>
    <table style="width: 100%;border-spacing: 0px !important;">
        <thead>
            <tr>
                <td style="text-align:left; width: 50%;">
                    <img style="max-width: 120px;" src="{{public_path($logo)}}" />
                </td>
                <td style="text-align:right;font-weight:400; width: 50%;">
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
">{{$invoice_no}}</span>
                    <h5 style="
margin-bottom: 5px;
margin-top: 0px;
">
                        Create Date
                    </h5>
                    <span style="font-size: 12px;margin-bottom: 0.5rem;display: block;">{{ date('d/m/Y', strtotime($create_date))}}</span>
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
">{{date('d/m/Y', strtotime($due_date))}}</span>
                </td>
            </tr>
            <tr>
                <td style="height:10px;"></td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="height:12px;width: 50%;">
                    <h5 style="font-weight:600;margin-bottom: 10px !important;">FROM</h5>
                    <p style="
margin-top: 0px !important;
margin-bottom: 10px !important;
font-size: 12px;
">
                        School Name : {{$from_name}}
                    </p>
                    <p style="
margin-top: 0px !important;
margin-bottom: 10px !important;
font-size: 12px;
">
                        phone Number : {{$from_phone}}
                    </p>
                    <p style="
margin-top: 0px !important;
margin-bottom: 10px !important;
font-size: 12px;
">
                        Email : {{$from_email}}
                    </p>
                    <p style="
margin-top: 0px !important;
margin-bottom: 10px !important;
font-size: 12px;
">
                        Address : {{$from_address}}
                    </p>
                </td>
                <td style="
text-align: right;width: 50%;
">
                    <h5 style="font-weight:600;margin-bottom: 10px !important;">TO</h5>
                    <p style="
margin-top: 0px !important;
margin-bottom: 10px !important;
font-size: 12px;text-align: right;
">
                        Name : {{$to_name}}
                    </p>
                    <p style="
margin-top: 0px !important;
margin-bottom: 10px !important;
font-size: 12px;text-align: right;
">
                        phone Number : {{$to_phone}}
                    </p>
                    <p style="
margin-top: 0px !important;
margin-bottom: 10px !important;
font-size: 12px;text-align: right;
">
                        Email : {{$to_email}}
                    </p>
                    <p style="
margin-top: 0px !important;
margin-bottom: 10px !important;
font-size: 12px;text-align: right;
">
                        Work : {{$to_job}}
                    </p>
                </td>
            </tr>
            <tr>
                <td style="height:10px;"></td>
            </tr>
            <tr>
                <td>
                    <h5 style="font-weight:600;margin-bottom: 10px !important;">STUDENT INFORMATION</h5>
                    <p style="
margin-top: 0px !important;
margin-bottom: 10px !important;
font-size: 12px;
">
                        Name : {{$student_name}}
                    </p>
                    <p style="
margin-top: 0px !important;
margin-bottom: 10px !important;
font-size: 12px;
">
                        Grade : {{$student_grade}}
                    </p>
                </td>
            </tr>
            <tr>
                <td style="height:25px;"></td>
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
            @foreach ($items as $item)
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
                    {{$sub_total}}Ks
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
                    {{$discount}}%
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
                    {{$grand_total}}Ks
                </td>
            </tr>
            <tr>
                <td style="height:10px;"></td>
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
margin-bottom: 10px !important;
font-size: 12px;
">
                        Bank Name : {{$bank_name}}
                    </p>
                    <p style="
margin-top: 0px !important;
margin-bottom: 10px !important;
font-size: 12px;
">
                        Account Holder : {{$bank_holder_name}}
                    </p>
                    <p style="
margin-top: 0px !important;
margin-bottom: 10px !important;
font-size: 12px;
">
                        Account Number : {{$bank_no}}
                    </p>
                </td>
                <td style="
text-align:right;
">
                    <div style="text-align:center;">
                        <img style="max-width: 100px; margin-top:1.5rem;" src="{{public_path($signature)}}" />
                        <p style="text-align:center;font-size: 12px; margin-bottom:0px;">Signature</p>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>