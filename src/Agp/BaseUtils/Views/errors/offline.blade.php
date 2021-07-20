@extends('layout.app')

@section('title', 'Sem internet')

@section('contentFull')
    <div class="d-flex flex-row-fluid flex-column bgi-size-cover bgi-position-center bgi-no-repeat p-10 p-sm-30">
        <div class="row flex-row-fluid">
            <div class="col-lg-6">
                <h1 class="font-weight-boldest text-primary display2 display1-md mt-15">Sem internet</h1>
                <p class="font-size-h6 text-dark-50 font-weight-bold">Você parece estar sem conexão com a internet!</p>
                <div>
                    <a href="javascript:window.location.reload(true)" class="btn btn-primary font-weight-bold">Tentar novamente</a>
                </div>
            </div>
            <div class="col-lg-6 d-flex flex-column justify-content-end align-items-end">
                <img class="w-100 w-md-50 max-w-200px max-w-lg-300px float-right mt-md-n15" src="{{ asset('media/misc/no-connection.png') }}" alt="">
            </div>
        </div>
    </div>
@endsection
