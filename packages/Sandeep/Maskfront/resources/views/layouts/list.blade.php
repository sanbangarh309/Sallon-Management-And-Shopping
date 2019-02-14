<div class="card">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#storm_chat" aria-controls="profile" id="get_crnt_api_id" role="tab" data-toggle="tab">Strom</a></li>
                        @for ($i = 0; $i < count($frnds); $i++)
                            <li role="presentation" ><a href="#{{ $frnds[$i]['id'] }}" aria-controls="profile" class="get_crnt_id" data-id="{{$frnds[$i]['id']}}" role="tab" data-toggle="tab">{{ $frnds[$i]['name'] }}<span class="unreadmessage pull-right"><i class="fa fa-commenting" aria-hidden="true">{{ $frnds[$i]['count'] }}</i></span></a></li>
                        @endfor
                            <li><a href="{{url('/dologout')}}" aria-controls="Logout">Logout</a></li>
                        </ul>
                    </div>