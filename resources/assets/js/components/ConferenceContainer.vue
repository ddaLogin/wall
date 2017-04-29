<template>
    <div class="text-center conference-container" id="screen">

        <div class="streams-container">
            <button v-if="!connection" v-on:click="join" class="btn btn-success">
                <span v-if="!connecting">Join to conference</span>
                <span v-if="connecting"><i class="fa fa-spinner fa-spin fa-2x" aria-hidden="true"></i></span>
            </button>

            <div v-if="connection" v-for="stream in streams" class="col col-md-3">
                <video id="remoteStream" :src="stream" controls autoplay="true"></video>
            </div>
        </div>

        <div class="conference-sidebar">
            <h4>Participants</h4>
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
            <div class="messages-container">
                <ul class="messages">
                    <li v-for="message in messages" :class="isMy(message.user.id)">
                        <img :src="message.user.photo_mini" alt="" class="avatar">
                        <div class="text_wrapper">
                            <div class="text">
                                {{message.text}}
                            </div>
                        </div>
                    </li>
                </ul>
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
            <video class="stream" id="stream" autoplay="true"></video>
            <img v-if="!this.video"  :src="this.photo" class="stream-photo">
            <div class="btn-group pull-right stream-control" role="group" aria-label="...">
                <button v-on:click="soundToggle" type="button" class="btn btn-default btn-xs">
                    <i v-if="this.audio" class="fa fa-microphone text-success" aria-hidden="true"></i>
                    <i v-if="!this.audio" class="fa fa-microphone-slash text-danger" aria-hidden="true"></i>
                </button>
                <button v-on:click="videoToggle" type="button" class="btn btn-default btn-xs ">
                    <i class="fa fa-video-camera text-success" aria-hidden="true"></i>
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
                        "mandatory": { "maxWidth": "320", "maxHeight": "240", "maxFrameRate": "25" },
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
                this.video = true;
                this.audio = true;
            },
            getMediaStreamError: function (error) {
                console.error('Could\'t get user media stream: ' + error);
            },
            onRemoteStream: function (event) {
                this.streams.push(URL.createObjectURL(event.stream));
            },
            soundToggle: function () {
                this.stream.getAudioTracks()[0].enabled = !(this.stream.getAudioTracks()[0].enabled);
                this.audio = !this.audio;
            },
            videoToggle: function () {
                this.stream.getVideoTracks()[0].enabled = !(this.stream.getVideoTracks()[0].enabled);
                this.video = !this.video;
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
//                this.peers.forEach(function (item) {
//                    item.connection.close();
//                });
//                this.connection = false;
//                this.participants = {};
//                this.connection= false;
//                this.connecting= false;
//                this.streams= [];
//                this.participants= {};
//                this.peers= {};
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
                if (this.userId == userId){
                    return 'message right appeared';
                } else {
                    return 'message left appeared';
                }
            },
            scrollToEnd: function() {
                var vm = this;
                setTimeout(function(){
                    var container = vm.$el.querySelector(".messages");
                    container.scrollTop = container.scrollHeight;
                }, 100);
            },
        },
    }
</script>