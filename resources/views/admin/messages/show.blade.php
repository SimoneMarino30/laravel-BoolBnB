@extends('layouts.app')

@section('page-name', 'Messaggio ' . $message->id)

@section('content')
    <section class="container text-center pt-4">

        @if (session('message_content'))
            <div class="alert alert-{{ session('message_type') ? session('message_type') : 'success' }}">
                {{ session('message_content') }}
            </div>
        @endif

        <h3 class="my-4 text-primary">Messaggio da {{ $message->email }}</h3>

        <div class="card my-4">
            <div class="card-header d-flex justify-content-between">
                <strong>
                    {{ $message->name }} {{ $message->surname }}
                </strong>
                <span class="text-primary">
                    {{ $message->email }}
                </span>
            </div>
            <div class="card-body">
                <p>
                    {{ $message->text }}
                </p>
            </div>
            <div class="card-footer text-primary">
                Inviato: {{ $message->created_at }}
            </div>
        </div>
    </section>
@endsection
