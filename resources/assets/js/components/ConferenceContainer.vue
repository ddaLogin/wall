<template>
    <div class="text-center conference-container" id="screen">

        <div class="streams-container">
            <button v-if="!connection" v-on:click="join" class="btn btn-success">
                <span v-if="!connecting">
                    <i class="fa fa-sign-in" aria-hidden="true"></i>
                    Join to conference
                </span>
                <span v-if="connecting"><i class="fa fa-spinner fa-spin fa-2x" aria-hidden="true"></i></span>
            </button>

            <div v-if="connection" v-for="stream in streams" class="stream">
                <video class="stream" id="remoteStream" :src="stream.stream" controls autoplay="true"></video>
                <h4 class="text-center nickname text-primary">{{stream.user.nickname}}</h4>
            </div>
        </div>

        <div class="conference-sidebar">
            <h4>
                <i class="fa fa-users" aria-hidden="true"></i>
                Participants
                <button v-if="connection" v-on:click="showFriendList" class="btn btn-success btn-xs">
                    <i class="fa fa-user-plus" aria-hidden="true"></i>
                </button>
            </h4>
            <hr>
            <ul class="list-group text-left">
                <li v-for="participant in participants" class="list-group-item margin-0 padding-0">
                    <a target="_blank" :href="participant.link"><img class="photo-mini" :src="participant.photo_mini" alt=""></a>
                    <a target="_blank" :href="participant.link">{{participant.nickname}}</a>
                </li>
            </ul>
            <h5 v-if="!connection" class="text-center text-warning">You are not in room</h5>
            <button v-if="connection" v-on:click="leave" class="btn btn-danger leave">Leave conference</button>
        </div>

        <div class="chat-container">
            <div class="messages-container panel-body msg_container_base">

                <div v-for="message in messages" class="row msg_container" v-bind:class="{ 'base_sent': isMy(message.user.id), 'base_receive': !isMy(message.user.id) }">
                    <div v-if="!isMy(message.user.id)" class="avatar">
                        <img :src="message.user.photo_mini" class=" img-responsive ">
                    </div>
                    <div class="messages" v-bind:class="{ 'msg_sent': isMy(message.user.id), 'msg_receive': !isMy(message.user.id) }">
                        <p>
                            <label v-if="!isMy(message.user.id)">{{message.user.nickname}}:</label>
                            {{message.text}}
                        </p>
                    </div>
                    <div v-if="isMy(message.user.id)" class="avatar">
                        <img :src="message.user.photo_mini" class=" img-responsive ">
                    </div>
                </div>

            </div>
            <div class="chat-input">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-commenting" aria-hidden="true"></i></span>
                    <input :disabled="!this.connection" v-model="message" v-on:keyup.13="send" type="text" class="form-control" placeholder="Message" aria-describedby="sizing-addon2">
                    <span class="input-group-btn">
                        <button :disabled="!this.connection" v-on:click="send" class="btn btn-default" type="button"><i class="fa fa-share" aria-hidden="true"></i></button>
                    </span>
                </div>
            </div>
        </div>

        <div class="local-stream-container-mini">
            <video class="stream" id="stream" autoplay="true" muted></video>
            <img v-if="!this.video"  :src="this.photo" class="stream-photo">
            <canvas id="micro" class="micro"></canvas>
            <div class="btn-group pull-right stream-control" role="group" aria-label="...">
                <button v-on:click="soundToggle" type="button" class="btn btn-default btn-xs" id="audio_controller">
                    <i v-if="this.audio" class="fa fa-microphone text-success" aria-hidden="true"></i>
                    <i v-if="!this.audio" class="fa fa-microphone-slash text-danger" aria-hidden="true"></i>
                </button>
                <button v-on:click="videoToggle" type="button" class="btn btn-default btn-xs" id="video_controller">
                    <i v-if="this.video" class="fa fa-video-camera text-success" aria-hidden="true"></i>
                    <i v-if="!this.video" class="fa fa-video-camera text-danger" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                userId: window.Laravel.userId,
                connection: false, //true when connection success
                connecting: false, // true when connection in process
                video: false, //true when you local stream have video
                audio: false, //true when you local stream have audio
                stream: null, //you local stream
                streams: [], //other streams
                participants: {}, //all room's members
                peers: {}, //all peers than you have
                messages: [],
                message: '',
                PeerConnection: window.mozRTCPeerConnection || window.webkitRTCPeerConnection,
                SessionDescription: window.mozRTCSessionDescription || window.RTCSessionDescription,
                IceCandidate: window.mozRTCIceCandidate || window.RTCIceCandidate,
                server: {
                    iceServers: [
                        {url: "stun:23.21.150.121"},
                        {url: "stun:stun.l.google.com:19302"},
                    ]
                },
                options: {
                    optional: [
                        {DtlsSrtpKeyAgreement: true}, // требуется для соединения между Chrome и Firefox
                        {RtpDataChannels: true} // требуется в Firefox для использования DataChannels API
                    ]
                },
            };
        },
        props: ['roomLink', 'photo'],
        mounted() {
            this.getMediaStream();
        },
        methods: {

            //Media stream part
            getMediaStream: function () {
                var streamConstraints = {
                    "audio": true,
                    "video": {
                        "mandatory": { "maxWidth": "640", "maxHeight": "480", "maxFrameRate": "25" },
                        "optional": []
                    }
                };

                if (navigator.webkitGetUserMedia) {
                    navigator.webkitGetUserMedia(streamConstraints, this.getMediaStreamSuccess, this.getMediaStreamError);
                } else {
                    navigator.mozGetUserMedia(streamConstraints, this.getMediaStreamSuccess, this.getMediaStreamError);
                }
            },
            getMediaStreamSuccess: function (stream) {
                document.getElementById('stream').src = URL.createObjectURL(stream);
                this.stream = stream;
                if (this.stream.getAudioTracks().length){
                    this.audio = this.stream.getAudioTracks()[0].enabled;
                } else {
                    this.audio = false;
                    document.getElementById('audio_controller').disabled = true;
                }

                if (this.stream.getVideoTracks().length){
                    this.video = this.stream.getVideoTracks()[0].enabled;
                } else {
                    this.video = false;
                    document.getElementById('video_controller').disabled = true;
                }

                window.AudioContext = window.AudioContext || window.webkitAudioContext;
                var audioContext = new AudioContext();
                var microphone = audioContext.createMediaStreamSource(stream);
                var javascriptNode = audioContext.createScriptProcessor(1024, 1, 1);
                var max_level_L = 0;
                var old_level_L = 0;
                var cnvs = document.getElementById("micro");
                var cnvs_cntxt = cnvs.getContext("2d");

                microphone.connect(javascriptNode);
                javascriptNode.connect(audioContext.destination);
                javascriptNode.onaudioprocess = function(event){

                    var inpt_L = event.inputBuffer.getChannelData(0);
                    var instant_L = 0.0;

                    var sum_L = 0.0;
                    for(var i = 0; i < inpt_L.length; ++i) {
                        sum_L += inpt_L[i] * inpt_L[i];
                    }
                    instant_L = Math.sqrt(sum_L / inpt_L.length);
                    max_level_L = Math.max(max_level_L, instant_L);
                    instant_L = Math.max( instant_L, old_level_L -0.008 );
                    old_level_L = instant_L;

                    cnvs_cntxt.clearRect(0, 0, cnvs.width, cnvs.height);
                    cnvs_cntxt.fillStyle = '#00ff00';
                    cnvs_cntxt.fillRect(10,10,(cnvs.width-20)*(instant_L/max_level_L),(cnvs.height-20)); // x,y,w,h

                }
            },
            getMediaStreamError: function (error) {
                console.error('Could\'t get user media stream: ' + error);
            },
            onRemoteStream: function (event) {
                var stream = URL.createObjectURL(event.stream);
                var video = (event.stream.getVideoTracks().length)?event.stream.getVideoTracks()[0].enabled:false;
                var user;

                _.forEach(this.peers, function(value, key) {
                    if (event.target == value.connection){
                        user = value.targetUser;
                    }
                });

                _.forEach(this.participants, function(value, key) {
                    if (user == value.id){
                        user = value;
                    }
                });

                this.streams.push({
                    stream:stream,
                    user:user,
                    video:video
                });
            },
            soundToggle: function () {
                if (this.stream.getAudioTracks().length) {
                    this.stream.getAudioTracks()[0].enabled = !(this.stream.getAudioTracks()[0].enabled);
                    this.audio = !this.audio;
                } else {
                    console.log('Your microphone not found');
                    document.getElementById('audio_controller').disabled = true;
                }
            },
            videoToggle: function () {
                if (this.stream.getVideoTracks().length) {
                    this.stream.getVideoTracks()[0].enabled = !(this.stream.getVideoTracks()[0].enabled);
                    this.video = !this.video;
                } else {
                    console.log('Your web camera not found');
                    document.getElementById('video_controller').disabled = true;
                }
            },


            //WebSocket part
            join: function () {
                var vm = this;
                this.connecting = true;
                window.Echo.join('Room.'+this.roomLink)
                    .here(this.here)
                    .joining(this.joining)
                    .leaving(this.leaving)
                    .listenForWhisper('new-offer', function(event){
                        if (vm.userId == event.target){
                            vm.createAnswer(event.user, event.offer);
                            vm.setCandidates(event.user, event.candidates);
                        }
                    })
                    .listenForWhisper('new-answer', function(event){
                        if (vm.userId == event.target){
                            vm.setAnswer(event.user, event.answer);
                            vm.setCandidates(event.user, event.candidates);
                        }
                    })
                    .listenForWhisper('new-message', function(event){
                        vm.messages.push(event.message);
                        vm.scrollToEnd();
                    });
            },
            leave: function () {
                _.forEach(this.peers, function(value, key) {
                    value.connection.close();
                });
                this.connection = false;
                this.participants = {};
                this.connection= false;
                this.connecting= false;
                this.streams= [];
                this.participants= {};
                this.peers= {};
                window.location  = '/';
            },
            here: function (members) { //when we complete join, open peer
                this.connection = true;
                this.connecting = false;
                members.forEach(this.createOffer);
                this.participants = members;
            },
            joining: function (joiningMember, members) {
                this.participants.push(joiningMember);
            },
            leaving: function (leavingMember, members) {
                if (this.peers[leavingMember.id] != undefined){
                    this.peers.connection.close();
                }
                this.participants.splice(this.participants.indexOf(leavingMember),1);
                this.peers.splice(this.peers.indexOf(leavingMember.id),1);
            },


            //WEBRtcConnection part
            getPeer: function (userId) {
                if (userId == this.userId) {
                    return null;
                }

                if (userId === undefined || this.peers[userId] === null || this.peers[userId] === undefined){
                    this.peers[userId] = {};
                    this.peers[userId].targetUser = userId;
                    this.peers[userId].candidates = [];
                    this.peers[userId].connection = new this.PeerConnection(this.server, this.options);
                }

                return this.peers[userId];
            },
            initPeer: function (peer, type) {
                var vm = this;
                peer.connection.onicecandidate = function (event) {
                    if (event.candidate){
                        peer.candidates.push(event.candidate);
                    } else {
                        if (type == 'offer'){
                            window.Echo.join('Room.'+vm.roomLink).whisper('new-offer', {target: peer.targetUser, user: vm.userId, offer: peer.connection.localDescription, candidates: peer.candidates});
                        } else if (type == 'answer'){
                            window.Echo.join('Room.'+vm.roomLink).whisper('new-answer', {target: peer.targetUser, user: vm.userId, answer: peer.connection.localDescription, candidates: peer.candidates});
                        }
                    }
                };
                peer.connection.onaddstream = this.onRemoteStream;
                peer.connection.addStream(this.stream);
            },
            createOffer: function (user) {
                var peer = this.getPeer(user.id);
                if (peer !== null){
                    this.initPeer(peer, 'offer');
                    peer.connection.createOffer(function(offer) {
                        peer.connection.setLocalDescription(offer);
                    }, function () {}, {});
                }
            },
            createAnswer: function (userId, offer) {
                var peer = this.getPeer(userId);
                if (peer !== null){
                    this.initPeer(peer, 'answer');
                    peer.connection.setRemoteDescription( new RTCSessionDescription(offer));
                    peer.connection.createAnswer(function(answer) {
                        peer.connection.setLocalDescription(answer);
                    }, function () {}, {'mandatory': { 'OfferToReceiveAudio':true, 'OfferToReceiveVideo':true }}
                    );
                }
            },
            setAnswer: function (userId, answer) {
                var peer = this.getPeer(userId);
                if (peer !== null){
                    peer.connection.setRemoteDescription( new RTCSessionDescription(answer));
                }
            },
            setCandidates: function (userId, candidates) {
                var peer = this.getPeer(userId);
                if (peer !== null){
                    candidates.forEach(function (candidate) {
                        peer.connection.addIceCandidate(new RTCIceCandidate(candidate));
                    });
                }
            },

            //chat
            send: function () {
                if (this.message != '' && this.connection){
                    var messageItem = {user: this.participants[0], text: this.message};
                    window.Echo.join('Room.'+this.roomLink).whisper('new-message', {message: messageItem});
                    this.messages.push(messageItem);
                    this.message = '';
                }
                this.scrollToEnd();
            },

            //helpers
            isMy: function (userId) {
                return (this.userId == userId);
            },
            scrollToEnd: function() {
                var vm = this;
                setTimeout(function(){
                    var container = vm.$el.querySelector(".messages-container");
                    container.scrollTop = container.scrollHeight;
                }, 100);
            },
            showFriendList: function () {
                this.$emit('friends');
            },
        },
    }
</script>