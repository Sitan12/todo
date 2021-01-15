@extends('layouts.app')

@section('content')

   <div class="container">
    <div class="row justify-content-center">
        <div class="col-xs">
            <a name="" id="" class="btn btn-primary m-2" href="{{route('todos.create')}}" role="button"> Ajouter une todo</a>
        </div>
        <div class="col-xs">
        @if(Route::currentRouteName()== 'todos.index')
        <a name="" id="" class="btn btn-warning m-2" href="{{route('todos.undone')}}" role="button"> Voir les todos ouvertes</a>
        </div>
        <div class="col-xs">
        <a name="" id="" class="btn btn-success m-2" href="{{route('todos.done')}}" role="button"> Voir les todos terminés</a>
        @elseif(Route::currentRouteName()== 'todos.done')
        </div>
        <div class="col-xs">
        <a name="" id="" class="btn btn-dark m-2" href="{{route('todos.index')}}" role="button"> Voir toutes les todos </a>
        </div>
        <div class="col-xs">
        <a name="" id="" class="btn btn-warning m-2" href="{{route('todos.undone')}}" role="button"> Voir les todos ouvertes</a>
        @elseif(Route::currentRouteName()== 'todos.undone')
        </div>
        <div class="col-xs">
        <a name="" id="" class="btn btn-dark m-2" href="{{route('todos.index')}}" role="button"> Voir toutes les todos </a>
        </div>
        <div class="col-xs">
        <a name="" id="" class="btn btn-warning m-2" href="{{route('todos.done')}}" role="button"> Voir les todos ouvertes</a>
        @endif
        </div>
    </div>
   </div>

@foreach($datas as $data)
<div class="alert alert-{{$data-> done ? 'success' : 'warning'}}" role="alert">
    <div class="row">
        <div class="col-sm">
            <p class="my-0">
                <strong>
                   <span class="badge badge-dark">
                   #{{$data->id}}
                   </span>
                </strong>
                <small>
                    créée {{ $data->created_at->from()}} par
                   {{ Auth::user()->id == $data->user->name ? 'moi' : $data->user->name }}

                   @if($data->TodoAffectedTo && $data->TodoAffectedTo->id == Auth::user()->id)
                   affectée à moi
                   $elseif($data->TodoAffectedTo)
                   {{$data->TodoAffectedTo ? ', affectée à' .$data->TodoAffectedTo->name : '' }}
                   @endif

                   @if($data->TodoAffectedTo && $data->TodoAffectedBy && $data->TodoAffectedBy->id == 
                   Auth::user()->id)
                   par moi meme
                   $elseif($data->TodoAffectedTo && $data->TodoAffectedBy && $data->TodoAffectedBy->id != 
                   Auth::user()->id)
                   par {{ $data->TodoAffectedBy->name }}
                   @endif
                </small>
                <small>
                    <p>
                    @if($data->done)
                Terminée {{ $data->updated_at->from()}} - Fait en 
                {{ $data->updated_at->diffForHumans($data->created_at,1)}}
                @endif
                    </p>
                </small>
            </p>
            <details>
            <summary>
                <strong> {{ $data->name}} @if($data->done)<span class="badge badge-success">done</span>@endif</strong>
            </summary>
                <p>{{$data->description}}</p>
            </details>
           
        </div>
        <div class="col-sm form-inline justify-content-end my-1">
        <!-- boutton affeter a un user -->
        <div class="dropdown">
            <button type="button" class="btn btn-secondary dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" 
            aria-haspopup="true" aria-expanded="false">Affecter à</button>
            <form action="" method="post">
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            @foreach($users as $user)

                <a class="dropdown-item" 
                href="/todos/{{$data->id}}/affectedto/{{$user->name}}">{{$user->name}}</a>
            @endforeach
            </div>
            </form>
        </div>
        <!-- bouton done/undone -->
            @if($data->done == 0)
            <form action="{{ route('todos.makedone', $data->id)}}" method="POST">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-success mx-1" style="width: 80px;">Done</button>
            </form>
            @else
            <form action="{{ route('todos.makeundone', $data->id)}}" method="POST">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-warning mx-1" style="width: 80px;">Undone</button>
            </form>
            @endif
    <!-- buttoon edit -->
    @can('edit',$data)
        <a id="" name="" class="btn btn-info mx-1 " href="{{ route('todos.edit', $data->id)}}">Edit</a>
    @elsecannot('edit', $data)
    <a id="" name="" class="btn btn-info mx-1 disabled" href="{{ route('todos.edit', $data->id)}}">Edit</a>
    @endcan
        <!-- boutton supprimer todo -->
        @can('delete',$data)
            <form action="{{ route('todos.destroy', $data->id)}}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger mx-1">Delete</button>
            </form>
        @elsecannot('edit', $data)
        <form action="{{ route('todos.destroy', $data->id)}}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger mx-1 disabled">Delete</button>
            </form>
        @endcan
        </div>
    </div>
</div>
@endforeach

{{$datas->links()}}

@endsection
