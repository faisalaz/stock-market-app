<!-- resources/views/result.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Historical Data</div>

                <div class="card-body">
                    <table id="dataTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Open</th>
                                <th>High</th>
                                <th>Low</th>
                                <th>Close</th>
                                <th>Volume</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($data))
                            @foreach($data as $entry)
                                    <tr>
                                        <td>{{ date('d-m-Y',$entry['date']) }}</td>
                                        <td>{{ round(isset($entry['open'])? $entry['open'] : 0.00,3) }}</td>
                                        <td>{{ round(isset($entry['high'])? $entry['high'] : 0.00,3) }}</td>
                                        <td>{{ round(isset($entry['low'])? $entry['low'] : 0.00,3) }}</td>
                                        <td>{{ round(isset($entry['close'])? $entry['close'] : 0.00,3) }}</td>
                                        <td>{{ round(isset($entry['volume'])? $entry['volume'] : 0.00,3) }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    $(document).ready( function () {
        $('#dataTable').DataTable({
            "paging": true, // Enable pagination
            "searching": true // Enable search
        });
    });
</script>
@endsection
