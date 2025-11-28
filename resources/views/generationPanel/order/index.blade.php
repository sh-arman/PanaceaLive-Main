@extends('layouts.generationpanel_master')

@section('content')
<style>
/* Minimal utility styles to enhance Bootstrap */
.truncate { max-width: 260px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.bootstrap-select { width: 100% !important; }
.table thead th { white-space: nowrap; }
/* Ensure full-width and full-height layout */
#content { width: 85%; min-height: 85vh; display: flex; flex-direction: column; }
.table-responsive, #box { width: 100%; }
/* Make controls and table adapt nicely */
.search-log .form-control, .search-log .btn, .search-log .bootstrap-select { width: 100%; }
</style>

<div id="content" class="container-fluid">
    <div class="row" style="margin-top:8px; margin-bottom:8px;">
        <div class="col-xs-12">
            <div class="row" style="display:flex; align-items:center;">
                <div class="col-xs-12 col-sm-8">
                    <h3 style="margin:6px 0; font-weight:600; font-size:18px;">Print Order Log</h3>
                </div>
                <div class="col-xs-12 col-sm-4 text-right">
                    <a href="{{ url('/logout') }}" class="btn btn-default btn-sm">Logout</a>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="row">
            <div class="col-xs-12">
                <div class="alert alert-success" style="margin: 8px 0;">
                    {{ session('success') }}
                </div>
            </div>
        </div>
    @endif
    @if ($errors && $errors->any())
        <div class="row">
            <div class="col-xs-12">
                <div class="alert alert-danger" style="margin: 8px 0;">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @if (count($order) > 0)
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default" style="margin-bottom:10px;">
                <div class="panel-heading" style="padding:8px 12px;">
                    <strong>Filters</strong>
                </div>
                <div class="panel-body" style="padding:10px 12px;">
                    <form class="search-log"> <!-- keep class for existing JS -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-3" style="margin-bottom:8px;">
                                <input type="text" class="form-control" placeholder="Search by Batch Number" name="batch">
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-3" style="margin-bottom:8px;">
                                <select class="selectpicker" id="printOrderSelect" multiple selected="selected" data-width="100%" title="Filter by medicine">
                                    @foreach($medicine as $med)
                                        <option>{{$med->medicine_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-3" style="margin-bottom:8px;">
                                <input type="button" class="input-sm form-control" name="daterange" value="Date"/>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-3" style="margin-bottom:8px;">
                                <button type="button" id="reset_button" class="btn btn-default" style="width:100%;">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <input type="hidden" name="company_id" value="{{$company->id}}">
            <input type="hidden" name="pagename" value="print_log_page">
            <div class="table-responsive" id="box">
                <table class="table table-striped table-bordered table-hover table-condensed">
                    <thead>
                    <tr>
                        <th>Medicine Name</th>
                        <th>Batch Number</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>File Name</th>
                        <th>Download CSV</th>
                        <th>Date</th>
                    </tr>
                    </thead>
                    <tbody class="inner_table">
                    @foreach ($order as $ord)
                        @php
                            $status = strtolower($ord->status ?? '');
                            $labelClass = 'label-warning';
                            if ($status === 'finished') $labelClass = 'label-success';
                            elseif ($status === 'failed' || $status === 'error') $labelClass = 'label-danger';
                            elseif ($status === 'processing') $labelClass = 'label-info';
                        @endphp
                        <tr>
                            <td>{{ $ord->medicine->medicine_name ." ". $ord->medicine->medicine_type ." " . $ord->medicine->medicine_dosage }}</td>
                            <td>{{ $ord->batch_number }}</td>
                            <td class="text-right">{{ number_format($ord->quantity) }}</td>
                            <td>
                                <span class="label {{ $labelClass }}">{{ ucfirst($ord->status) }}</span>
                            </td>
                            <td class="truncate" title="{{ $ord->file }}">{{ $ord->file }}</td>
                            <td>
                                <a class="btn btn-primary btn-xs" href="codes/{{$ord->file}}"
                                   download="{{strpos($ord->file, '_')!= false?explode('_', $ord->file, 2)[1]:$ord->file}}">Download</a>
                                @php $st = strtolower($ord->status ?? ''); @endphp
                                @if(in_array($st, ['processing','running']))
                                    <form method="POST" action="/order/{{ $ord->id }}/cancel" style="display:inline; margin-left:6px;">
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn btn-warning btn-xs" onclick="return confirm('Cancel this running order?');">Cancel</button>
                                    </form>
                                @endif
                            </td>
                            <td>{{ $ord->created_at }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div id="clientPager" class="text-center" style="margin:8px 0;">
                <button id="prevPage" class="btn btn-default btn-xs">Prev</button>
                <span id="pageInfo" style="margin: 0 10px;"></span>
                <button id="nextPage" class="btn btn-default btn-xs">Next</button>
            </div>
        </div>
    </div>

    <script>
        (function(){
            // Disable auto-load-on-scroll attached by global script for this page
            function disableScrollAutoLoad(){
                var page = document.querySelector('input[name="pagename"]');
                if (page && page.value === 'print_log_page' && window.jQuery) {
                    try { jQuery('#box').off('scroll'); } catch(e) {}
                }
            }

            function initClientPager(){
                var tbody = document.querySelector('.inner_table');
                if(!tbody) return;
                var initialRows = Array.prototype.slice.call(tbody.querySelectorAll('tr'));
                var rowsHtml = initialRows.map(function(tr){ return tr.outerHTML; });
                var pageSize = 25; // rows per page
                var current = 1;
                var totalPages = Math.max(1, Math.ceil(rowsHtml.length / pageSize));
                var prev = document.getElementById('prevPage');
                var next = document.getElementById('nextPage');
                var info = document.getElementById('pageInfo');

                function render(){
                    var start = (current - 1) * pageSize;
                    var end = Math.min(start + pageSize, rowsHtml.length);
                    tbody.innerHTML = rowsHtml.slice(start, end).join('');
                    if(info) info.textContent = 'Page ' + current + ' / ' + totalPages + ' (' + rowsHtml.length + ' rows)';
                    if(prev) prev.disabled = (current === 1);
                    if(next) next.disabled = (current === totalPages);
                }

                if(prev) prev.addEventListener('click', function(){ if(current > 1){ current--; render(); } });
                if(next) next.addEventListener('click', function(){ if(current < totalPages){ current++; render(); } });

                render();
            }

            if(document.readyState === 'loading'){
                document.addEventListener('DOMContentLoaded', function(){ disableScrollAutoLoad(); initClientPager(); });
            } else {
                disableScrollAutoLoad();
                initClientPager();
            }
        })();
    </script>
    @else
        <div class="row">
            <div class="col-xs-12">
                <div class="alert alert-info" style="margin: 8px 0;">No data available at the moment.</div>
            </div>
        </div>
    @endif
</div>

@endsection
