<div class="modal fade modal-primary" id="getCoordinatesFromMapModal" aria-hidden="true"
     aria-labelledby="getCoordinatesFromMapModal" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Harita Üzerinden Konumunuzu İşaretleyiniz</h4>
            </div>
            <div class="modal-body">
                <style>#gmapLoader {
                        background-color: #b00;
                        font-weight: bold;
                        color: #fff;
                        border: 2px solid #fff;
                        position: absolute;
                        z-index: 10;
                        text-align: left;
                        font-size: 14px;
                        font-family: Verdana,Geneva,Arial,Helvetica,sans-serif;
                        padding: 2px 6px;
                    }
                    .vpcAdvancedGoogleMapView div.text { display: none; }
                    .vpcAdvancedGoogleMapView .fromAddress { margin-top:6px; }
                    .vpcAdvancedGoogleMapView .fromAddress input { float: left; padding: 3px; }
                    .vpcAdvancedGoogleMapView .fromAddress input.textBefore { width: 375px; color: #5A5D60; }
                    .vpcAdvancedGoogleMapView .fromAddress input.vpsClearOnFocusBlurred { color: #aeaeae; }
                    .vpcAdvancedGoogleMapView .fromAddress input.textOn { color: #5A5D60; }
                    .vpcAdvancedGoogleMapView .fromAddress button {
                        float: right;
                        font-weight: bold;
                        margin: -3px 0px 0px 0px;
                        padding: 0px 5px;
                        overflow: visible;
                        cursor: pointer;
                    }
                    .vpcAdvancedGoogleMapView .mapDirSuggestParent { display: none; margin-top: 30px; }
                    .vpcAdvancedGoogleMapView .container { overflow: hidden; border: 1px solid #000; }
                    .vpcMapCoordinatesAdvancedGoogleMap .fromAddress input.textBefore { width: 655px; }
                    .vps-progress-window { padding: 15px; }
                    .vps-progress-window .vps-progress-content { margin-bottom: 10px; }
                    .vps-progress-window .vps-progress-text { font-size: 12px; }
                    .vpcMapCoordinatesAdvancedGoogleMapCoords .container { border: 1px solid #000; }
                    .vpcMapCoordinatesAdvancedGoogleMapCoords .targetAddress { margin-top: 10px; margin-bottom:5px; padding:10px; background-color: #ffce52; border:1px solid #cc9a1d; }
                    .vpcMapCoordinatesAdvancedGoogleMapCoords .targetAddress input.textBefore { float: left; width: 500px; padding: 5px; }
                    .vpcMapCoordinatesAdvancedGoogleMapCoords .targetAddress input.showLocation { float: right; overflow: visible; padding: 3px 3px; }
                    .vpcMapCoordinatesAdvancedGoogleMapCoords .mapDirSuggestParent { display: none; }
                    .vpcMapCoordinatesAdvancedGoogleMapCoords #pac-input {
                        width: 400px;
                        padding: 5px;
                        margin-top: 28px;
                        font-size: 14px;
                        font-weight: bold;
                    }
                    .vpcMapCoordinatesAdvancedGoogleMapCoords .infoWindow {
                        width: 250px;
                    }
                    .vpcMapCoordinatesAdvancedGoogleMapCoords .infoWindow input {
                        margin-top: 10px;
                    }
                    .vpcMapCoordinatesAdvancedGoogleMapCoords .infoWindow input.inputLat {
                        margin-left: 10px;
                    }
                    .vpcMapCoordinatesAdvancedGoogleMapCoords .infoWindow input.seaHeight {
                        margin-left: 6px;
                    }</style>
                <div class="row">
                    <div class="col-md-12">
                        <div class="webStandard vpcMapCoordinatesAdvancedGoogleMapCoords">
                            <input type="hidden" class="options" value="{'coordinates':'40.213189;28.967795','latitude':'40.213189','longitude':'28.967795','zoom_properties':1,'zoom':15,'height':500,'width':780,'scale':1,'satelite':1,'overview':1,'showSearchBox':1,'routing':1,'markers':[{'latitude':'40.213189','longitude':'28.967795','draggable':true}],'language':{'moveInfo':'Fareyi basılı tutarak sürükleyiniz.','lat':'Enlem','lng':'Boylam','seaHeight':'Deniz Seviyesi'}}" />
                            <input id="pac-input" class="controls" type="text" placeholder="Adresi bul">

                            <div class="container" style="height: 600px;"></div>

                            <div class="mapDir"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
                <button type="button" onclick="addCoordinates()" class="btn btn-primary">Kordinatı Ekle</button>
            </div>
        </div>
    </div>
</div>
