<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>E-Reporting</title>
    <link rel="shortcut icon" type="image/png" href="./favicon.png" />
    <style>
        * {
            box-sizing: border-box;
        }

        .table-bordered td,
        .table-bordered th {
            border: 1px solid #ddd;
            padding: 10px;
            word-break: break-all;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 16px;
        }

        .h4-14 h4 {
            font-size: 12px;
            margin-top: 0;
            margin-bottom: 5px;
        }

        .img {
            margin-left: "auto";
            margin-top: "auto";
            height: 30px;
        }

        pre,
        p {
            /* width: 99%; */
            /* overflow: auto; */
            /* bpicklist: 1px solid #aaa; */
            padding: 0;
            margin: 0;
        }

        table {
            font-family: arial, sans-serif;
            width: 100%;
            border-collapse: collapse;
            padding: 1px;
        }

        .hm-p p {
            text-align: left;
            padding: 1px;
            padding: 5px 4px;
        }

        td,
        th {
            text-align: left;
            padding: 8px 6px;
        }

        .table-b td,
        .table-b th {
            border: 1px solid #ddd;
        }

        th {
            /* background-color: #ddd; */
        }

        .hm-p td,
        .hm-p th {
            padding: 3px 0px;
        }

        .cropped {
            float: right;
            margin-bottom: 20px;
            height: 100px;
            /* height of container */
            overflow: hidden;
        }

        .cropped img {
            width: 400px;
            margin: 8px 0px 0px 80px;
        }

        .main-pd-wrapper {
            box-shadow: 0 0 10px #ddd;
            background-color: #fff;
            border-radius: 10px;
            padding: 15px;
        }

        .table-bordered td,
        .table-bordered th {
            border: 1px solid #ddd;
            padding: 10px;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <section class="main-pd-wrapper" style="width: 100%;">
        <div>
            {{-- <div style="display: table-header-group"> --}}
            <h4 style="text-align: center; margin: 0">
                <b>Medicines Outgoing Report</b>
            </h4>

            <table style="width: 100%; table-layout: fixed">
                <tr>
                    <td style="border-left: 1px solid #ddd; border-right: 1px solid #ddd">
                        <div
                            style="
                  text-align: center;
                  margin: auto;
                  line-height: 1.5;
                  font-size: 14px;
                  color: #4a4a4a;
                ">
                            <img src="{{ public_path('assets/images/logo.png') }}" width="150px">

                            <p style="font-weight: bold; margin-top: 15px">
                                ITB STIKOM BALI - KSR
                            </p>
                            {{-- <p style="font-weight: bold">
                                Toll Free No. :
                                <a href="tel:018001236477" style="color: #00bb07">1800-123-6477</a>
                            </p> --}}
                        </div>
                    </td>
                    <td align="right"
                        style="
                text-align: right;
                padding-left: 50px;
                line-height: 1.5;
                color: #323232;
              ">
                        <div>
                            <h4 style="margin-top: 5px; margin-bottom: 5px">
                                Report generate to list all outgoing medicines
                            </h4>
                            <p>The "Generate Report: Outgoing Medicines" report is a comprehensive document that
                                provides a detailed overview of all medicines dispensed or distributed during a
                                specified time period. This report serves as a critical tool for healthcare
                                administrators, pharmacists, and medical professionals to monitor medication usage</p>
                            {{-- <p style="font-size: 14px">
                                Aakriti Rathore,B-268, west vinod nagar, street no.2,near
                                press apartment bus stand, Patparganj, IP
                                Extension,Delhi,07,110092,Delhi(07)<br />
                                Tel:
                                <a href="tel:01241234568" style="color: #00bb07">0124-1234568</a>
                            </p> --}}
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <table class="table table-bordered h4-14" style="width: 100%; -fs-table-paginate: paginate; margin-top: 15px">
            <thead>
                {{-- <thead style="display: table-header-group"> --}}
                <tr
                    style="
              margin: 0;
              background: #fcbd021f;
              padding: 15px;
              padding-left: 20px;
              -webkit-print-color-adjust: exact;
            ">
                    <td colspan="4">
                        <h3>
                            E-Reporting for Medicines Outgoing
                            <p
                                style="
                    font-weight: 300;
                    font-size: 85%;
                    color: #626262;
                    margin-top: 7px;
                  ">
                                E-Report Code:
                                <span style="color: #00bb07">Code {{generateBatchNumber('REPORT')}}</span><br />
                            </p>
                        </h3>
                    </td>
                    <td colspan="5">
                        <p>Print Date:- {{ now() }}</p>
                        <p style="margin: 5px 0">Filter Date:- {{ $start }} until
                            {{ $end }}</p>
                    </td>
                    <td colspan="4" style="width: 300px">
                        <h4 style="margin: 0">Print By:</h4>
                        <p>
                            {{ auth()->user()->name }},<br />
                            {{ ucfirst(auth()->user()->role) }} - {{ auth()->user()->phone }}
                        </p>
                    </td>
                </tr>

                <tr>
                    <th style="width: 50px">#</th>
                    <th style="width: 150px">
                        <h4>Medicine Name</h4>
                    </th>
                    <th style="width: 80px">
                        <h4>
                            Medicine<br />
                            Code
                        </h4>
                    </th>
                    <th style="width: 80px">
                        <h4>
                            Category
                        </h4>
                    </th>
                    <th style="width: 60px" colspan="2">
                        <h4>Batch Number
                        </h4>
                    </th>
                    {{-- <th style="width: 80px">
                        <h4>Stock</h4>
                    </th> --}}

                    <th style="width: 80px">
                        <h4>
                            Expired<br />
                            Date
                        </h4>
                    </th>
                    <th style="width: 80px">
                        <h4>
                            Quantity
                        </h4>
                    </th>
                    <th style="width: 80px">
                        <h4>
                            Unit
                        </h4>
                    </th>
                    <th colspan="4" style="width: 300px">
                        <h4>
                            Description
                        </h4>
                    </th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                @endphp
                @forelse ($data as $key => $outgoing)
                @foreach ($outgoing->details as $index => $detail)
                <tr>
                    @php
                        $total += $detail->quantity;
                    @endphp
                    @if ($index === 0)
                    <td rowspan="{{count($outgoing->details)}}">{{$key+1}}</td>
                    @endif
                    <td>{{$detail->medicine->name}}</td>
                    <td>{{$detail->medicine->medicine_code}}</td>
                    <td>{{$detail->medicine->category->name}}</td>
                    <td colspan="2">{{$detail->batch->batch_number}}</td>
                    {{-- <td>{{$detail->batch->stock}}</td> --}}
                    <td>{{$detail->batch->expired_date}}</td>
                    <td>{{$detail->quantity}}</td>
                    <td>{{$detail->medicine->unit}}</td>
                    @if ($index === 0)
                    <td rowspan="{{count($outgoing->details)}}" colspan="4">{{$outgoing->description}}</td>
                    @endif
                </tr>
                @endforeach
                @empty
                <tr>
                    <td colspan="13" style="text-align: center">
                        <h3>No data</h3>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <table class="hm-p table-bordered" style="width: 100%; margin-top: 30px">
            <tr>
                <th style="width: 400px">
                    Medicines Outgoing Total
                </th>
                <td style="vertical-align: top; color: #000">
                    <b>{{$total}}</b>
                </td>
            </tr>
        </table>

        <table style="width: 20%; margin-top: 30px; position: absolute; right: 15px;">
            <tr>
                <td style="text-align: center">Mengetahui,</td>
            </tr>
            <tr>
                <td style="text-align: center">Ketua umum KSR ITB STIKOM Bali,</td>
            </tr>
            <tr>
                <td style="text-align: center"><br><br><br><br>(Frantika Putri)</td>
            </tr>
        </table>
    </section>
</body>

</html>
