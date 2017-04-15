<template>
    <div>
        <form class="navbar-form navbar-left">
            <div class="form-group">
                <input v-on:keyup="search" v-model="q"  type="text" class="form-control" placeholder="Search" data-toggle="dropdown">
                <ul class="dropdown-menu search-dropdown-menu" aria-labelledby="dropdownMenuDivider" v-if="users || posts">
                    <!--<li v-if="users" class="dropdown-header">Users</li>-->
                    <!--<li v-for="user in users">-->
                        <!--<hr class="margin-0 padding-0">-->
                        <!--<a v-bind:href="user.link" >-->
                            <!--<img :src="user.photo_link_mini" alt="" class="photo-mini-search">-->
                            <!--{{user.nickname}}-->
                        <!--</a>-->
                    <!--</li>-->
                    <li v-if="posts" class="dropdown-header">Posts</li>
                    <li v-for="post in posts">
                        <hr class="margin-0 padding-0">
                        <a v-bind:href="post.link" >
                            <img class="photo-mini-search" :src="post.author.photo_link_mini" alt="">
                            <span v-for="tag in toJson(post.searched_tags)" class="label label-primary margin-right-4" v-html="asHashtag(tag)"></span>
                            <br>
                            <span v-html="post.searched_text"></span>
                        </a>
                    </li>
                </ul>
            </div>
            <button type="submit" class="btn btn-default"><i class="fa fa-search" aria-hidden="true"></i></button>
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
            };
        },
        methods: {
            search: function () {
                if (this.q.length >= 3){
                    axios.get('/search/'+this.q).then(this.searchSuccess);
                }
            },
            searchSuccess: function (response) {
                this.users = (response.data.users.length)?response.data.users:null;
                this.posts = (response.data.posts.length)?response.data.posts:null;
                $(".form-group").addClass('open');
            },
            toJson: function (tags) {
                var t = JSON.parse(tags);
                return t;
            },
            asHashtag: function (tag) {
                return '#'+tag;
            }
        },
    }
</script>
