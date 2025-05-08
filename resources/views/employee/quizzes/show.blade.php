@extends('layouts.app')

@section('page_title')
    Categories
@endsection

@section('content')
    <table class="table table-striped table-hover table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @if (count($records) > 0)
            @foreach ($records as $record)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $record->title }}</td>
                <td>{{ $record->description }}</td>
                {{-- continue with specify design --}}
            </tr>
            @endforeach
        </tbody>
        @else
            No data found
        @endif
    </table>
    {{ $records->links() }}
@endsection
