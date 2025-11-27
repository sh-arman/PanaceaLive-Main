@extends('layouts.front_consumerengagement')

@section('assets')
    <link rel="stylesheet" type="text/css" href="{{ asset('consumerEngagement/css/responsive-table.css') }}">
@endsection

@section('title', 'Campaign Report')
@section('content')
    <div id="reportpage">
        <div class="report-container">
            <h2>Report</h2>
            <div class="inputs">
                <h3 class="input-label">Medicine</h3>
                <select>
                    <option>All</option>
                    <option>Rolac</option>
                    <option>Maxpro</option>
                </select>
            </div>
            <div class="inputs">
                <h3 class="input-label">Language</h3>
                <select>
                    <option>All</option>
                    <option>English</option>
                    <option>Bengali</option>
                </select>
            </div>
            <div class="inputs">
                <h3 class="input-label">Time</h3>
                <input class="datetime" type="text" name="time"> to <input class="datetime" type="text" name="time">
            </div>
            <table>
                <caption>Statement Summary</caption>
                <thead>
                <tr>
                    <th scope="col"></th>
                    <th scope="col">Campaign Name</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Language</th>
                    <th scope="col">Message Sent</th>
                    <th scope="col">Campaign status</th>
                    <th scope="col">Created At</th>
                    <th scope="col">Execution Time</th>

                </tr>
                </thead>
                <tbody>
                <tr>
                    <td data-label="SL">1</td>
                    <td data-label="Campaign">Youtube My</td>
                    <td data-label="Amount">1000</td>
                    <td data-label="Language">English</td>
                    <td data-label="Message-Sent">200</td>
                    <td data-label="Status">Ongoing</td>
                    <td data-label="Created">Aug 16, 2017</td>
                    <td data-label="Execution">Aug 17, 2017</td>
                    {{--<td> <a href = 'cancelCampaign/{{$template_data->id}}'> Cancel Campaign </a>  </td>--}}
                </tr>
                <tr>
                    <td data-label="SL">2</td>
                    <td data-label="Campaign">Stomach Ache</td>
                    <td data-label="Amount">1200</td>
                    <td data-label="Language">Bangla</td>
                    <td data-label="Message-Sent">150</td>
                    <td data-label="Status">Ongoing</td>
                    <td data-label="Created">Aug 18, 2017</td>
                    <td data-label="Execution">Aug 19, 2017</td>
                    {{--<td> <a href = 'cancelCampaign/{{$template_data->id}}'> Cancel Campaign </a>  </td>--}}
                </tr>
                <tr>
                    <td data-label="SL">3</td>
                    <td data-label="Campaign">Lorem Ipsum</td>
                    <td data-label="Amount">900</td>
                    <td data-label="Language">English</td>
                    <td data-label="Message-Sent">100</td>
                    <td data-label="Status">Ongoing</td>
                    <td data-label="Created">Aug 19, 2017</td>
                    <td data-label="Execution">Aug 19, 2017</td>
                    {{--<td> <a href = 'cancelCampaign/{{$template_data->id}}'> Cancel Campaign </a>  </td>--}}
                </tr>
                <tr>
                    <td data-label="SL">4</td>
                    <td data-label="Campaign">Ipsum Lorem</td>
                    <td data-label="Amount">800</td>
                    <td data-label="Language">Bangla</td>
                    <td data-label="Message-Sent">600</td>
                    <td data-label="Status">Ongoing</td>
                    <td data-label="Created">Aug 20, 2017</td>
                    <td data-label="Execution">Aug 20, 2017</td>
                    {{--<td> <a href = 'cancelCampaign/{{$template_data->id}}'> Cancel Campaign </a>  </td>--}}
                </tr>

                </tbody>
            </table>

        </div>
    </div>

    @include('partials._consumerengagement_footer')
    <script type="text/javascript">
        $('select').select2();
        $(".datetime").flatpickr({
            enableTime: true,
            altInput: true,
            minDate: "today",
            defaultDate: "today",
        });
    </script>
@stop