---
layout: tutorial_frame
title: WMS example
---
<script type='text/javascript'>

	var map = L.map('m ', {
		center: [-17, -67],
		zoom: 3
	});

	var wmsLayer = L.tileLayer.wms('http://demo.opengeo.org/geoserver/ows?', {
		layers: 'ne:ne'
	}).addTo(map);

</script>