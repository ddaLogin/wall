@extends('layouts.base')

@section('title', 'Wall conference')

@section('body')
    <conference-container v-on:friends="showFriendList" room-link="{{$room->link}}" photo="{{Auth::user()->photo_link}}"></conference-container>

    <!-- Modal -->
    <div class="modal fade" id="friendsList" tabindex="-1" role="dialog" aria-labelledby="friendsListLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="friendsListLabel">Invite to conference</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1"><i class="fa fa-search" aria-hidden="true"></i></span>
                            <input v-model="q" type="text" class="form-control" placeholder="Search in friends">
                        </div>
                    </form>
                    <hr>
                    <ul class="list-group">
                        <li v-for="friend in filteredFriends" class="list-group-item">
                            <a target="_blank" :href="friend.link">
                                <img class="photo-mini" :src="friend.photo_link_mini" alt="">
                                @{{friend.nickname}}
                            </a>
                            <button v-if="!friend.invited" v-on:click="invite(friend)" type="button" class="pull-right btn btn-primary btn-xs">
                                <i class="fa fa-user-plus" aria-hidden="true"></i>
                                Invite
                            </button>
                            <button v-if="friend.invited" type="button" class="pull-right btn btn-success btn-xs">
                                <i class="fa fa-check" aria-hidden="true"></i>
                                Invited
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        window.mix = {
            data: {
                q: '',
                roomLink: '{!! $room->link !!}',
                friends: {!! json_encode($friends) !!},
            },
            computed: {
                filteredFriends : function () {
                    var friends = this.friends;
                    var q = this.q.toLowerCase();
                    var data = [];
                    friends.forEach(function (element, index, array) {
                        if (element.nickname.toLowerCase().indexOf(q) != -1) {
                            data.push(element);
                        }
                    });
                    return data;
                },
            },
            methods : {
                showFriendList:function () {
                    $('#friendsList').modal('show');
                },
                invite: function (user) {
                    var vm = this;
                    axios.get('/room/'+this.roomLink+'/invite/'+user.id).then(function (response) {
                        user.invited = true;
                        vm.$forceUpdate();
                    }).catch(this.inviteError);
                },
                inviteError: function (error) {
                    console.log('Invite failed with error: ' + error);
                }
            }
        };
    </script>
@endsection