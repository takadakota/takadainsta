@foreach ($users as $user)
    <h1>{{$user->name}}. is god Your mail address is {{$user->email}}</h1>
@endforeach
