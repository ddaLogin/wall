<template>
    <div>
        <form class="navbar-form navbar-left">
            <div class="form-group">
                <input v-on:keyup="search" v-model="q"  type="text" class="form-control" placeholder="Search" data-toggle="dropdown">
                <ul class="dropdown-menu search-dropdown-menu" aria-labelledby="dropdownMenuDivider" v-if="users || posts">
                    <li v-if="users" class="dropdown-header">Users</li>
                    <li v-for="user in users"><a v-bind:href="user.link" >
                        <img :src="user.photo_link" alt="" class="photo-mini-search">
                        {{user.nickname}}
                    </a></li>
                    <li v-if="posts" class="dropdown-header">Posts</li>
                    <li v-for="post in posts"><a v-bind:href="post.link" v-html="highlight(post.search_text)"></a></li>
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
            highlight: function (text) {
                var reg = new RegExp(this.q, "i");
                var originQ = text.match(reg);
                return text.replace(reg, '<span class=\'highlight\'>' + originQ + '</span>');
            }
        },
    }
</script>
