@extends('layouts.app')
@section('page-title', 'My Asset')
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Asset List</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                <table class="table table-condensed table-striped table-bordered table-sm">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Serial Number</th>
                            <th>Item Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assetData as $index => $asset)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                {{ $asset->receivedSerial->requisitionDetail->grnItemDetail->item->item_no . '-' . $asset->receivedSerial->asset_serial_no ?? config('global.null_value') }}
                            </td>
                            <td>{{ $asset->item->item_description }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-danger">No assets found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
