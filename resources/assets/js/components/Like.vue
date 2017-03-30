<template>
    <div>
        <div class="btn-group" role="group" aria-label="...">
            <button v-on:click="setLike()" type="button" class="btn btn-sm btn-default" v-bind:class="{'btn-success': isLike()}">
                <i class="fa fa-thumbs-o-up" aria-hidden="true"></i> {{likes}}
            </button>
            <button v-on:click="setDislike()" type="button" class="btn btn-sm btn-default" v-bind:class="{'btn-danger': isDislike()}">
                <i class="fa fa-thumbs-o-down" aria-hidden="true"></i> {{dislikes}}
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                status: null,
                likes: 0,
                dislikes: 0,
                responseCount: 0, //TODO: разобраться с синхронизацией при спаме и оптиместическим поведением
            };
        },
        props:['likeStatus', 'likeCount', 'dislikeCount', 'postId'],
        methods: {
            setLike: function () {
                this.optimisticToggle(true);
                this.responseCount++;
                axios.post('/like/toggle', {post_id:this.postId, like:true}).then(this.setSuccess).catch(this.setError);
            },
            setDislike: function () {
                this.optimisticToggle(false);
                this.responseCount++;
                axios.post('/like/toggle', {post_id:this.postId, like:false}).then(this.setSuccess).catch(this.setError);
            },
            setSuccess: function (response) {
                if (this.responseCount <= 1){
                    this.likes = response.data.likes.length;
                    this.dislikes = response.data.dislikes.length;
                    this.status = (response.data.like == null)?null:response.data.like.like;
                } else this.responseCount--;
            },
            setError: function (error) {
                window.location.reload();
            },
            isLike: function () {
                if(this.status == true) return true;
                else false;
            },
            isDislike: function () {
                if(this.status == false) return true;
                else false;
            },
            optimisticToggle: function (newStatus) {
                if(this.status === null){ //set like or dislike
                    if(newStatus){
                        this.likes++;
                    } else {
                        this.dislikes++;
                    }
                    this.status = newStatus;
                } else if(this.status == newStatus){ //remove like
                    this.status = null;
                    if (newStatus) this.likes--;
                    else this.dislikes--;
                } else { //switch status
                    if(newStatus){
                        this.likes++;
                        this.dislikes--;
                    } else {
                        this.dislikes++;
                        this.likes--;
                    }
                    this.status = newStatus;
                }
            },
        },
        mounted() {
            this.status = (!this.likeStatus.length)?null:this.likeStatus;
            this.likes = this.likeCount;
            this.dislikes = this.dislikeCount;
        }
    }
</script>
