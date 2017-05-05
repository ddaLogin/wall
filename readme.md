<p align="center"><img src="https://s8.hostingkartinok.com/uploads/images/2017/05/27bb4b850fc5a3d90fbc14838eda8591.png"></p>

## About "Wall"
"Wall" is a simple social portal. Where users can:
- publish post with tags
- set like or dislike for posts
- search another users or posts
- subscribe on friends
- watch top of posts
- watch posts him subscriptions
- make online video conference

## Technical part
- App based on [Laravel 5.4 framework](https://laravel.com/)
- Database - [PostgreSQL](https://www.postgresql.org/)
- Cache - [Redis](https://redis.io/)
- HTML and CSS based on [Bootstrap](http://getbootstrap.com/)
- JavaScript based on [Vue.js](https://vuejs.org/)
- For web socket, used [Laravel Echo Server](https://github.com/tlaverdure/laravel-echo-server) with [Node.js](https://nodejs.org/en/) and [Redis](https://redis.io/)
- For conferences, used [WebRTC](https://webrtc.org/) technology by Google

#### Technical details and about deploy

###### Frontend tools

For that would build frontend part, you need use [npm](https://www.npmjs.com/) tool, for download all javascript dependencies, and then run script "production" to build js and css code.
How to make all of this, you can read in [documentation of npm](https://docs.npmjs.com/)

---

###### Web Sockets

For the operation of sockets, you should install [Laravel Echo Server](https://github.com/tlaverdure/laravel-echo-server), after installing you should init and run it from project directory.
Also for the operation of sockets is required to be installed [Redis](https://redis.io/) and the [app must be configured](https://laravel.com/docs/5.4/redis#configuration) to work with it.

---

###### Conference 

Conference based on [WebRTC](https://webrtc.org/) technology by Google. It used only on client machine, and you don't need additional server-side elements, but app must run with [https](https://en.wikipedia.org/wiki/HTTPS) protocol. To make this you will needed [SSL](https://www.globalsign.com/en/ssl-information-center/what-is-an-ssl-certificate/) certificate, you can buy real certificate, or [generate fake certificate](http://www.akadia.com/services/ssh_test_certificate.html)  or you can use mine fake certificate, from root of this repository, [Laravel Echo Server](https://github.com/tlaverdure/laravel-echo-server) also  must use https protocol with SSL certificate. For conferences you must use Google Chrome , in the future i make conference to mozila.

---

###### Search

Search on site based on [Full Text Search by PostgreSQL](https://www.postgresql.org/docs/9.5/static/textsearch.html) database

---
<small>This app was made only for portfolio, you can't watch it in the web.</small>