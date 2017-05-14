<template>
    <div class="text-center">
        <h4 class="text-center">
            <i v-if="uploadComplete" class="fa fa-check text-success" aria-hidden="true"></i>
            <i v-if="uploadProgress" class="fa fa-refresh fa-spin text-info" aria-hidden="true"></i>
            Profile photo
        </h4>
        <img class="width-percentage-100" v-bind:src="url" alt="Profile photo" id="photoImage" >
        <div class="text-center">
            <button v-on:click="photoChooseDialog" type="button" class="btn btn-default btn-xs"><i class="fa fa-camera" aria-hidden="true"></i> Choose photo</button>
            <button v-if="photoSelected" v-on:click="photoReset" type="button" class="btn btn-warning btn-xs"><i class="fa fa-times" aria-hidden="true"></i> Reset</button>
            <button v-if="photoSelected" v-on:click="photoUpload" type="button" class="btn btn-success btn-xs"><i class="fa fa-cloud-upload" aria-hidden="true"></i> Upload</button>
        </div>
        <input v-on:change="photoChosen" type="file" class="hidden" accept="image/*" id="photoInput">
    </div>
</template>

<script>
    export default {
        mounted() {
            this.url = this.src;
        },
        data: function () {
            return {
                url: '',
                jcropPlugin: null,
                photoSelected: false,
                uploadComplete: false,
                uploadProgress: false,
                coordinate: null,
            }
        },
        props: ['src', 'uploadUrl'],
        methods:  {
            photoChooseDialog: function () {
                document.getElementById("photoInput").click();
            },
            photoChosen: function (e) {
                if (e.target.files[0] == undefined) {
                    this.reset();
                    return;
                }
                this.photoSelected = true;

                var reader = new FileReader();
                reader.onload = this.renderImage;
                reader.readAsDataURL(e.target.files[0]);
            },
            photoReset: function () {
                document.getElementById("photoImage").src = this.url;
                document.getElementById("photoInput").value = null;
                this.photoSelected = false;
                this.coordinate = null;
                this.jCropDestroy();
            },
            photoUpload: function () {
                this.uploadProgress = true;
                var data = new FormData();
                data.append('photo', document.getElementById('photoInput').files[0]);
                data.append('x', Math.ceil(this.coordinate.x));
                data.append('y', Math.ceil(this.coordinate.y));
                data.append('h', Math.ceil(this.coordinate.w));
                data.append('w', Math.ceil(this.coordinate.w));
                axios.post(this.uploadUrl, data).then(this.photoUploadSuccess);
            },
            photoUploadSuccess: function (response) {
                this.photoSelected = false;
                this.uploadProgress = false;
                this.uploadComplete = true;
                this.url = response.data.url;
                this.photoReset();
            },
            renderImage: function (e) {
                this.jCropDestroy();
                var startCropFunction = this.setCrop;
                var size = {};
                var image = new Image();
                image.onload = function() {
                    size.width = this.width;
                    size.height = this.height;
                    // get loaded data and render thumbnail.
                    document.getElementById("photoImage").src = e.target.result;
                    startCropFunction(size);
                };
                image.src = e.target.result;
            },
            setCrop: function (size) {
                var coordUpd = this.coordinatesUpdates;
                this.jcropPlugin = jCrop(document.getElementById("photoImage"), {
                    setSelect: [ 0, 0, 400, 400 ],
                    trueSize: [size.width, size.height],
                    aspectRatio: 1,
                    canDrag: true,
                    canResize: true,
                    onSelect: coordUpd,
                });
            },
            jCropDestroy: function () {
                if (this.jcropPlugin){
                    document.getElementById("photoImage").style = '';
                    this.jcropPlugin.destroy();
                    this.jcropPlugin = null;
                }
            },
            coordinatesUpdates: function (coord) {
                this.coordinate = coord;
            },
        }
    }
</script>
