describe("Control.Scale", function () {
	it("can be added to an unloaded map", functio () {
		var map = L.map(document.createElement('div'));
		new L.Control.Scale().addTo(map);
	});
});
