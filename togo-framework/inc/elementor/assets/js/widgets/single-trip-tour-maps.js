(function ($) {
  "use strict";

  // Elementor handler function
  var TogoTourMapsHandler = function ($scope, $) {
    // Get the coordinates from the data attribute
    const mapElement = document.getElementById("togo-st-tour-maps-map");
    const coordinatesData = mapElement.getAttribute("data-coordinates");
    const lineColor = mapElement.getAttribute("data-line-color");
    const arrowColor = mapElement.getAttribute("data-arrow-color");
    const arrowSpeed = parseInt(mapElement.getAttribute("data-arrow-speed"));
    const mapZoom = parseInt(mapElement.getAttribute("data-map-zoom"));

    // Animation function for moving the icon along the polyline
    function animateCircle(line) {
      let count = 0;

      window.setInterval(() => {
        count = (count + 1) % 200;

        const icons = line.get("icons");
        icons[0].offset = count / 2 + "%";
        line.set("icons", icons);
      }, arrowSpeed);
    }

    // Function to add custom HTML markers with an index
    function CustomMarker(position, map, iconClass, index) {
      this.position = position;
      this.map = map;
      this.index = index;

      // Create the custom div element for the marker with index
      this.div = document.createElement("div");
      this.div.className = `custom-marker ${iconClass}`;
      this.div.style.position = "absolute";
      this.div.innerHTML = `<span class="marker-index">${index}</span>`; // Display index inside the marker

      // Add the custom marker to the map as an overlay
      this.setMap(map);
    }

    // Extend OverlayView to position the custom marker
    CustomMarker.prototype = new google.maps.OverlayView();
    CustomMarker.prototype.onAdd = function () {
      const panes = this.getPanes();
      panes.overlayMouseTarget.appendChild(this.div);
    };

    CustomMarker.prototype.draw = function () {
      const overlayProjection = this.getProjection();
      const pos = overlayProjection.fromLatLngToDivPixel(this.position);

      // Position the marker
      this.div.style.left = `${pos.x - 8}px`; // Center by offsetting half the icon width
      this.div.style.top = `${pos.y - 9}px`; // Center by offsetting half the icon height
    };

    CustomMarker.prototype.onRemove = function () {
      if (this.div.parentNode) {
        this.div.parentNode.removeChild(this.div);
      }
    };
    // Parse the JSON string into an array of coordinate strings
    const coordinatesArray = JSON.parse(coordinatesData);

    // Convert the coordinate strings into an array of objects
    const pathCoordinates = coordinatesArray.map((coord) => {
      const [lat, lng] = coord.split(",").map(Number); // Convert to numbers
      return { lat, lng }; // Return an object
    });

    const map = new google.maps.Map(mapElement, {
      center: pathCoordinates[0], // Center the map on the first coordinate
      zoom: mapZoom,
      mapTypeId: "terrain",
    });

    // Define the symbol, using one of the predefined paths ('CIRCLE')
    const lineSymbol = {
      path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
      scale: 3,
      strokeColor: arrowColor,
    };

    // Create the polyline and add the symbol to it via the 'icons' property
    const line = new google.maps.Polyline({
      path: pathCoordinates,
      icons: [
        {
          icon: lineSymbol,
          offset: "100%",
        },
      ],
      geodesic: true,
      strokeColor: lineColor,
      strokeOpacity: 1.0,
      strokeWeight: 3,
      map: map,
    });

    // Add a marker at each coordinate along the polyline path
    pathCoordinates.forEach((position, index) => {
      new CustomMarker(position, map, "custom-marker-icon", index + 1); // Pass index + 1 for 1-based index
    });

    animateCircle(line);
  };

  // Initialize when Elementor is ready
  $(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/togo-st-tour-maps.default",
      TogoTourMapsHandler
    );
  });
})(jQuery);
