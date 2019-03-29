var markers = [];

	var icon1 = jQuery("#marker1").val();

	var icon2 = jQuery("#marker2").val();

function searchSallon(id,type){
  window.location.href = 'https://mask-app.com/en/search?type='+type+'&sr='+id+'&wr=';
}

function initialize() {
	// var ajax_url = jQuery('#ajax_url_input').val();
	var locations = jQuery('#sallons_array').val();
	var location = '';
	var san_lat = '24.713552';
	var san_long = '46.675296';

    if(locations.length!='0'){
    try {
      var location = jQuery.parseJSON(locations);
    }catch(err){
    var location = locations;
    }
    // console.log(location);
    	var san_lat = location['0']['latitude'];
    	var san_long = location['0']['longitude'];

    }

    var zoom_limit = 12;
    // center:new google.maps.LatLng(san_lat,san_long),

    var mapProp = {
      center:new google.maps.LatLng(san_lat,san_long),
      zoom:1,
      mapTypeId:google.maps.MapTypeId.ROADMAP,
      mapTypeControl:false,
      streetViewControl: false,
      fullscreenControl: false,
      };

    var map=new google.maps.Map(document.getElementById("googleMap_search"),mapProp);

    var infowindow = new google.maps.InfoWindow();
    var marker, i, j;

    var bounds = new google.maps.LatLngBounds();

    if(location.length!='0'){

        for (i = 0; i < location.length; i++) {

        	if(location[i]['type']=='cuser'){
							j = "U";
					}else{
						j = i;
					}

	        marker = new google.maps.Marker({
	            position: new google.maps.LatLng(location[i]['latitude'], location[i]['longitude']),
	            title: location[i]["name"],
	            map: map,
	            id: location[i]["id"],
	      //       label:{
			    //   // text: ""+j+"",
			    //   color: "white",
			    //   fontSize: "15px",
			    //   fontWeight: "bold",
			    // } ,
			    icon: icon1,
	        });

	        marker.set("id", location[i]["id"]);

	        markers.push(marker);

          	google.maps.event.addListener(marker, 'click', (function(marker, i) {
	            return function() {
	            	if(location[i]['type']=='cuser'){
						infowindow.setContent('<div class="info_content">'+location[i]['name']+'</div>');
              				infowindow.open(map, marker);
	            	}else{
									var link = 'booking/'+location[i]['id']+'?tab=profile';
									var img  = 'https://mask-app.com/files/'+location[i]['avatar'];
	            		infowindow.setContent('<div class="info_content"><div class="col-sm-4"><img src="'+img+'" class="pro_img" style="width:80%"></img></div><div class="col-sm-8"><h3><a class="map_title" href="'+link+'">'+location[i]['name']+'</a><p class="map_add"><b>Address:</b><br/> '+location[i]['address']+'</p></h3></div></div>');
              				infowindow.open(map, marker);
	            	}

	            }
          	})(marker, i));

          	var geocoder = new google.maps.Geocoder();

        }

    	for (var s = 0; s < markers.length; s++) {
				bounds.extend(markers[s].getPosition());
			}

		map.fitBounds(bounds);

        marker.setMap(map);
    }

}

function hover(id) {
    for ( var i = 0; i< markers.length; i++) {
        if (id === markers[i].id) {
           markers[i].setIcon(icon2);
           break;
        }
   }
}

//Function called when out the div
function out(id) {
    for ( var i = 0; i< markers.length; i++) {
        if (id === markers[i].id) {
           markers[i].setIcon(icon1);
           break;
        }
   }
}
