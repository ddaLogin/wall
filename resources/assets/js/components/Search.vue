<template>
    <div>
        <form class="navbar-form navbar-left" method="get" :action="action">
            <div class="form-group" id="dropdown-toggle">
                <input id="search-input" v-on:keyup="search" v-model="q" name="q"  type="text" class="form-control" placeholder="Search" autocomplete="off" data-toggle="dropdown">
                <ul class="dropdown-menu search-dropdown-menu" aria-labelledby="dropdownMenuDivider" v-if="typeIs('user')">
                    <li class="dropdown-header">Users</li>
                    <li><hr class="margin-0 padding-0"></li>
                    <li v-for="user in users">
                        <a v-bind:href="user.link" >
                            <img :src="user.photo_link_mini" alt="" class="photo-mini-search">
                            <span v-html="user.searched_nickname"></span><br>
                            <span v-html="user.searched_email"></span>
                        </a>
                    </li>
                    <li v-if="!users" class="text-center text-warning">Users not found</li>
                    <li class="text-center">
                        <hr class="margin-0 padding-5">
                        <button v-on:click.stop="setType('post')" type="button" class="btn btn-primary btn-xs">go to posts</button>
                    </li>
                </ul>
                <ul class="dropdown-menu search-dropdown-menu" aria-labelledby="dropdownMenuDivider" v-if="typeIs('post')">
                    <li class="dropdown-header">Posts</li>
                    <li><hr class="margin-0 padding-0"></li>
                    <li v-for="post in posts">
                        <a v-bind:href="post.link" >
                            <img class="photo-mini-search" :src="post.author.photo_link_mini" alt="">
                            <span v-for="tag in toJson(post.searched_tags)" class="label label-primary margin-right-4" v-html="asHashtag(tag)"></span>
                            <br>
                            <span v-html="post.searched_text"></span>
                        </a>
                    </li>
                    <li v-if="!posts" class="text-center text-warning">Posts not found</li>
                    <li class="text-center">
                        <hr class="margin-0 padding-5">
                        <button v-on:click.stop="setType('user')" type="button" class="btn btn-primary btn-xs">go to users</button>
                    </li>
                </ul>
            </div>
            <button type="submit" class="btn btn-default">
                <i v-if="!loading" class="fa fa-search" aria-hidden="true"></i>
                <i v-if="loading" class="fa fa-spinner fa-spin" aria-hidden="true"></i>
            </button>
        </form>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                q: '',
                users: null,
                posts: null,
                loading: false,
                type: 'user', //can be 'user', 'post'
            };
        },
        props: ['action', 'oldQ'],
        methods: {
            search: function () {
                if (this.q.length >= 3){
                    this.loading = true;
                    var q = encodeURIComponent(this.q);
                    axios.get('/search?q='+q).then(this.searchSuccess);
                }
            },
            searchSuccess: function (response) {
                this.users = (response.data.users.length)?response.data.users:null;
                this.posts = (response.data.posts.length)?response.data.posts:null;
                $("#dropdown-toggle").addClass('open');
                this.loading = false;
            },
            typeIs: function (value) {
                return (this.type == value);
            },
            setType: function (value) {
                this.type = value;
                $("#dropdown-toggle").addClass('open');
                if (typeof(Storage) !== undefined) {
                    localStorage.setItem("search_type", this.type);
                }
            },
            toJson: function (tags) {
                var t = JSON.parse(tags);
                return t;
            },
            asHashtag: function (tag) {
                return '#'+tag;
            }
        },
        mounted() {
            if (typeof(Storage) !== undefined && localStorage.getItem("search_type")!='' && localStorage.getItem("search_type")!=undefined) {
                this.type = localStorage.getItem("search_type");
            }
            this.q = this.oldQ;
        }
    }
</script>
