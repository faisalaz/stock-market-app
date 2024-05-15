<!-- resources/views/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Stock Market Data</div>
                <div class="card-body">
                     @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form id="stockForm" action="{{ route('fetch.data') }}" method="POST">
                        @csrf
                       <div class="mb-3">
                            <label for="symbol" class="form-label">Company Symbol</label>
                             <select class="form-control" id="symbol" name="symbol">
                                <option value="">Select Company Symbol</option>
                            </select>

                            <!-- <input type="text" class="form-control" id="symbol" name="symbol" placeholder="Company Symbol" value="{{ old('symbol') }}"> -->
                            <div id="symbolError" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="text" class="form-control datepicker" id="start_date" name="start_date" placeholder="Start Date" value="{{ old('start_date') }}">
                            <div id="startDateError" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="text" class="form-control datepicker" id="end_date" name="end_date" placeholder="End Date" value="{{ old('end_date') }}">
                            <div id="endDateError" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{ old('email') }}">
                            <div id="emailError" class="invalid-feedback"></div>
                        </div>

                        <button type="submit" class="btn btn-primary" id="submitButton"> <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span> Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
 <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Fetch symbol options and populate select field with Select2

          $('#symbol').select2({
            ajax: {
                url: 'https://pkgstore.datahub.io/core/nasdaq-listings/nasdaq-listed_json/data/a5bc7580d6176d60ac0b2142ca8d7df6/nasdaq-listed_json.json',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                id: item.Symbol,
                                text: item.Symbol
                            }
                        })
                    };
                },
                cache: true
            },
            minimumInputLength: 1, // Minimum characters to trigger the search
            escapeMarkup: function (markup) { return markup; } // Allows markup to be injected into the results
        });
         // Set the initial value if present
        var initialValue = "{{ old('symbol') }}";
        if (initialValue !== '') {
            $('#symbol').append('<option value="' + initialValue + '" selected="selected">' + initialValue + '</option>');
        }

        $(".datepicker").datepicker({ dateFormat: 'yy-mm-dd' });


        $("#stockForm").submit(function(event) {
            $('#submitButton').prop('disabled', true);
            $('#submitButton .spinner-border').removeClass('d-none');
            event.preventDefault();
            var symbol = $("#symbol").val();
            var startDate = $("#start_date").val();
            var endDate = $("#end_date").val();
            var email = $("#email").val();

            // Client-side validation
            var symbolError = "";
            var startDateError = "";
            var endDateError = "";
            var emailError = "";

            if (symbol === "") {
                symbolError = "Company Symbol is required";
            }
            if (startDate === "") {
                startDateError = "Start Date is required";
            }
            if (endDate === "") {
                endDateError = "End Date is required";
            }
            if (email === "") {
                emailError = "Email is required";
            } else if (!isValidEmail(email)) {
                emailError = "Invalid email format";
            }

            if (startDate > endDate) {
                startDateError = "Start Date must be less than End Date";
            }

            if (endDate < startDate) {
                endDateError = "End Date must be greater than End Date";
            }

            // Display server-side validation errors
            $("#symbolError").text("{{ $errors->first('symbol') }}");
            $("#startDateError").text("{{ $errors->first('start_date') }}");
            $("#endDateError").text("{{ $errors->first('end_date') }}");
            $("#emailError").text("{{ $errors->first('email') }}");

            // Display client-side validation errors
            $("#symbolError").text(symbolError);
            $("#startDateError").text(startDateError);
            $("#endDateError").text(endDateError);
            $("#emailError").text(emailError);

            // If there are no errors, submit the form
            if (!symbolError && !startDateError && !endDateError && !emailError) {
                $('.invalid-feedback').toggle(false);
                this.submit();
            }
            else{
                $('.invalid-feedback').toggle(true);
            }
        });

        function isValidEmail(email) {
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
    });
</script>
@endsection
