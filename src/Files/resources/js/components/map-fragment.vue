<!--<map-fragment ref="map" @submit="submitMap"/>-->
<!---->
<!--<button @click="openMap()" class="btn btn-warning w-100 mx-auto mb-2">انتخاب لوکیشن</button>-->


<!--submitMap(latitude, longitude) {-->
<!----this.item.latitude = latitude-->
<!----this.item.longitude = longitude-->
<!--},-->
<!--openMap() {-->
<!----this.$refs.map.show(this.item.latitude == ''?null:this.item.latitude, this.item.longitude== ''?null:this.item.longitude)-->
<!--},-->

<template>
    <div v-if="showMap" @click.self="hide()"  class="map d-flex justify-content-center align-items-center">
        <div class="bg-white d-flex flex-column position-relative w-75" style="height: 90vh;">
            <div class="w-100 d-flex justify-content-between align-items-center bg-warning" style="height: 55px">
                <div style="visibility: hidden;">ssssss</div>
                <span>{{title}}</span>
                <div class="px-2" @click="hide()">
                    <i class="fas fa-arrow-left"></i>
                </div>
            </div>
            <div id="mapContactPage" class="w-full" style="height: calc(100% - 55px);"></div>
            <div class="position-absolute w-100" style="bottom: 0;">
                <div @click="submitAddress()" class="d-flex justify-content-center align-items-center rounded bg-warning p-1 m-1">ثبت</div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props:{
        title:{
            type: String,
            default: 'انتخاب آدرس'
        }
    },
    data(){
        return{
            showMap: false,

            myMap: null,

            lat: null,
            lon: null,

            layer: null,
            marker: null,
        }
    },
    methods:{
        show(lat=null, log=null){
            this.showMap = true;

            const script = LoadScript.load('/js/ol.js');
            script.then(() => {
                this.initMap(lat, log)

                if(lat && log){
                    this.createMarker(lat, log)
                }

                this.myMap.on('click', (evt)=>{
                    var coords = ol.proj.toLonLat(evt.coordinate);
                    if(this.marker){
                        this.removeMarker(this.marker)
                    }

                    this.createMarker(coords[1], coords[0])
                });
            });
        },
        hide(){
            this.showMap = false;
        },
        createMarker(lat, log){
            this.lat = lat;
            this.log = log;
            this.marker = new ol.Feature(new ol.geom.Point(ol.proj.fromLonLat([log, lat])));
            this.layer.getSource().addFeature(this.marker);
        },
        removeMarker(marker){
            this.layer.getSource().removeFeature(marker);
            this.marker=null;
        },
        submitAddress(){
            this.$emit('submit', this.lat, this.log);
            this.hide();
        },
        initMap(lat, log){
            this.myMap = new ol.Map({
                target: 'mapContactPage',
                key: 'web.36b771693eea4b4e8539af7ce48fc620',
                maptype: 'dreamy',
                poi: true,
                traffic: false,
                view: new ol.View({
                    center: ol.proj.fromLonLat([log?? 48.3457672, lat?? 33.4661711]),
                    zoom: 15
                })
            });
            this.layer = new ol.layer.Vector({
                source: new ol.source.Vector(),
                style: new ol.style.Style({
                    image: new ol.style.Icon({
                        anchor: [0.5, 1],
                        src: '/images/icons/ic_marker.png'
                    })
                })
            });
            this.myMap.addLayer(this.layer);
        },
    },
}
</script>

<style scoped>
.map{
    position: fixed;
    width: 100%;
    height: 100vh;
    top: 0;
    left: 0;
    z-index: 9999;
    background-color: rgba(0, 0, 0, 0.4);
}
</style>

