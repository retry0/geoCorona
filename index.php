<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Menampilkan data json menjadi peta dalam kasus data COVID-19 dengan sumber dari https://api.kawalcorona.com">
  <meta name="author" content="retry0">

  <title>geo Corona Indonesia</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css">
  <link rel="stylesheet" href="assets/lib/Leaflet.ExtraMarkers/css/leaflet.extra-markers.min.css">
  <link rel="stylesheet" href="assets/app.css">
</head>
<body>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
  <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"></script>
  <script src="assets/lib/Leaflet.ExtraMarkers/js/leaflet.extra-markers.min.js"></script>

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="#"><i class="fas fa-map-marker-alt"></i> Indonesia</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="#" data-toggle="modal" data-target="#infoModal"><i class="fas fa-info-circle"></i> Info</a>
        </li>
      </ul>
    </div>
  </nav>
  <!-- Modal -->
  <div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-dark text-light">
          <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-info-circle"></i> Info</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="card alert-dark p-3">
            Peta ini menggunakan data kasus COVID-19 dari <a href="https://api.kawalcorona.com/indonesia/provinsi/" target="_blank">https://api.kawalcorona.com/indonesia/provinsi/</a> yang digabungkan dengan file <a href="data/provinsi_point.geojson" target="_blank">provinsi_point.geojson</a> menggunakan PHP menjadi geojson layer baru berupa <a href="geojson.php" target="_blank">geojson.php</a> yang secara otomatis ketika ada perubahan data dari <a href="https://api.kawalcorona.com/indonesia/provinsi/" target="_blank">https://api.kawalcorona.com/indonesia/provinsi/</a> maka info kasus positif, kasus sembuh, dan kasus meninggal akan otomatis berubah.<br>
            Klasifikasi jumlah kasus mengikuti klasifikasi dari BNPB.
            <hr>
            <a href="https://github.com/retry0/geoCorona" type="button" class="btn btn-primary btn-sm btn-block" target="_blank"><i class="fab fa-github"></i> Source Code</a>
          </div>
        </div>
        <div class="modal-footer">
            <div class="col text-left">
              <a class="btn btn-link btn-sm" type="button" href="#" target="_blank">retry0@2020</a>
            </div>
            <div class="col text-right">
              <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
            </div>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <div class="row row-map">
      <div class="col-sm">
        <div id="map"></div>
      </div>
    </div>
    <div class="row row-info">
      <?php
        $dataIndonesia = file_get_contents("https://api.kawalcorona.com/indonesia/");
        $kasusIndonesia = json_decode($dataIndonesia);

        foreach($kasusIndonesia as $item){
      ?>
      <div class="col-sm-4 text-center text-warning bg-dark">
        <div class="row p-3">
          <div class="col-3">
            <i class="far fa-sad-tear fa-4x"></i>
          </div>
          <div class="col text-left">
            <h5><strong>TOTAL POSITIF</strong></h5>
            <h5><?php echo $item->positif; ?> orang</h5>
          </div>
        </div>
      </div>
      <div class="col-sm-4 text-center text-success bg-dark">
        <div class="row p-3">
          <div class="col-3">
            <i class="far fa-smile fa-4x"></i>
          </div>
          <div class="col text-left">
            <h5><strong>TOTAL SEMBUH</strong></h5>
            <h5><?php echo $item->sembuh; ?> orang</h5>
          </div>
        </div>
      </div>
      <div class="col-sm-4 text-center text-danger bg-dark">
        <div class="row p-3">
          <div class="col-3">
            <i class="far fa-frown fa-4x"></i>
          </div>
          <div class="col text-left">
            <h5><strong>TOTAL MENINGGAL</strong></h5>
            <h5><?php echo $item->meninggal; ?> orang</h5>
          </div>
        </div>
      </div>
      <?php } ?>
    </div>
  </div>
  
  <script>
    /* Initial Map */
    var map = L.map('map').setView([-2.4058653,117.5021489],5);

    var _attribution = '<a href="https://retry0.netlify.com/" target="_blank">retry0@2020</a>';
    
    /* Tile Basemap */
    var basemap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: _attribution
    });
    basemap.addTo(map);

    var kasusrendah = L.geoJson(null, {
      pointToLayer: function (feature, latlng) {
        if (feature.properties) {
          var lightgreenMarker = L.ExtraMarkers.icon({
            icon: 'fa-number',
            number: feature.properties.Kasus_Positif,
            markerColor: 'green-light',
            shape: 'square',
            prefix: 'fa',
            tooltipAnchor: [15, -25]
          });
          return L.marker(latlng, {
            icon: lightgreenMarker,
            riseOnHover: true
          });
        }
      },
      onEachFeature: function (feature, layer) {
        if (feature.properties) {
          var content = "<div class='card'>" +
          "<div class='card-header alert-success text-center p-1'><strong>Provinsi<br>" + feature.properties.PROV + "</strong></div>" +
          "<div class='card-body p-0'>" +
            "<table class='table table-responsive-sm m-0'>" +
              "<tr><th><i class='far fa-sad-tear'></i> Kasus Positif</th><th>" + feature.properties.Kasus_Positif + "</th></tr>" +
              "<tr class='text-success'><th><i class='far fa-smile'></i> Kasus Sembuh</th><th>" + feature.properties.Kasus_Sembuh + "</th></tr>" +
              "<tr class='text-danger'><th><i class='far fa-frown'></i> Kasus Meninggal</th><th>" + feature.properties.Kasus_Meninggal + "</th></tr>" +
            "</table>" +
          "</div>";
          layer.on({
            click: function (e) {
              kasusrendah.bindPopup(content);
            },
            mouseover: function (e) {
              kasusrendah.bindTooltip("Prov. " + feature.properties.PROV);
            }
          });
        }
      },
      filter: function(feature, layer) {
        return (feature.properties.Kasus_Positif <= 5);
      }
    });

    var kasusagakrendah = L.geoJson(null, {
      pointToLayer: function (feature, latlng) {
        if (feature.properties) {
          var darkblueMarker = L.ExtraMarkers.icon({
            icon: 'fa-number',
            number: feature.properties.Kasus_Positif,
            markerColor: 'blue-dark',
            shape: 'square',
            prefix: 'fa',
            tooltipAnchor: [15, -25]
          });
          return L.marker(latlng, {
            icon: darkblueMarker,
            riseOnHover: true
          });
        }
      },
      onEachFeature: function (feature, layer) {
        if (feature.properties) {
          var content = "<div class='card'>" +
          "<div class='card-header alert-primary text-center p-1'><strong>Provinsi<br>" + feature.properties.PROV + "</strong></div>" +
          "<div class='card-body p-0'>" +
            "<table class='table table-responsive-sm m-0'>" +
              "<tr><th><i class='far fa-sad-tear'></i> Kasus Positif</th><th>" + feature.properties.Kasus_Positif + "</th></tr>" +
              "<tr class='text-success'><th><i class='far fa-smile'></i> Kasus Sembuh</th><th>" + feature.properties.Kasus_Sembuh + "</th></tr>" +
              "<tr class='text-danger'><th><i class='far fa-frown'></i> Kasus Meninggal</th><th>" + feature.properties.Kasus_Meninggal + "</th></tr>" +
            "</table>" +
          "</div>";
          layer.on({
            click: function (e) {
              kasusagakrendah.bindPopup(content);
            },
            mouseover: function (e) {
              kasusagakrendah.bindTooltip("Prov. " + feature.properties.PROV);
            }
          });
        }
      },
      filter: function(feature, layer) {
        return (feature.properties.Kasus_Positif >= 6 && feature.properties.Kasus_Positif <= 19);
      }
    });

    var kasussedang = L.geoJson(null, {
      pointToLayer: function (feature, latlng) {
        if (feature.properties) {
          var orangeMarker = L.ExtraMarkers.icon({
            icon: 'fa-number',
            number: feature.properties.Kasus_Positif,
            markerColor: 'orange',
            shape: 'square',
            prefix: 'fa',
            tooltipAnchor: [15, -25]
          });
          return L.marker(latlng, {
            icon: orangeMarker,
            riseOnHover: true
          });
        }
      },
      onEachFeature: function (feature, layer) {
        if (feature.properties) {
          var content = "<div class='card'>" +
          "<div class='card-header alert-warning text-center p-1'><strong>Provinsi<br>" + feature.properties.PROV + "</strong></div>" +
          "<div class='card-body p-0'>" +
            "<table class='table table-responsive-sm m-0'>" +
              "<tr><th><i class='far fa-sad-tear'></i> Kasus Positif</th><th>" + feature.properties.Kasus_Positif + "</th></tr>" +
              "<tr class='text-success'><th><i class='far fa-smile'></i> Kasus Sembuh</th><th>" + feature.properties.Kasus_Sembuh + "</th></tr>" +
              "<tr class='text-danger'><th><i class='far fa-frown'></i> Kasus Meninggal</th><th>" + feature.properties.Kasus_Meninggal + "</th></tr>" +
            "</table>" +
          "</div>";
          layer.on({
            click: function (e) {
              kasussedang.bindPopup(content);
            },
            mouseover: function (e) {
              kasussedang.bindTooltip("Prov. " + feature.properties.PROV);
            }
          });
        }
      },
      filter: function(feature, layer) {
        return (feature.properties.Kasus_Positif >= 20 && feature.properties.Kasus_Positif <= 50);
      }
    });

    var kasustinggi = L.geoJson(null, {
      pointToLayer: function (feature, latlng) {
        if (feature.properties) {
          var redMarker = L.ExtraMarkers.icon({
            icon: 'fa-number',
            number: feature.properties.Kasus_Positif,
            markerColor: 'red',
            shape: 'square',
            prefix: 'fa',
            tooltipAnchor: [15, -25]
          });
          return L.marker(latlng, {
            icon: redMarker,
            riseOnHover: true
          });
        }
      },
      onEachFeature: function (feature, layer) {
        if (feature.properties) {
          var content = "<div class='card'>" +
          "<div class='card-header alert-danger text-center p-1'><strong>Provinsi<br>" + feature.properties.PROV + "</strong></div>" +
          "<div class='card-body p-0'>" +
            "<table class='table table-responsive-sm m-0'>" +
              "<tr><th><i class='far fa-sad-tear'></i> Kasus Positif</th><th>" + feature.properties.Kasus_Positif + "</th></tr>" +
              "<tr class='text-success'><th><i class='far fa-smile'></i> Kasus Sembuh</th><th>" + feature.properties.Kasus_Sembuh + "</th></tr>" +
              "<tr class='text-danger'><th><i class='far fa-frown'></i> Kasus Meninggal</th><th>" + feature.properties.Kasus_Meninggal + "</th></tr>" +
            "</table>" +
          "</div>";
          layer.on({
            click: function (e) {
              kasustinggi.bindPopup(content);
            },
            mouseover: function (e) {
              kasustinggi.bindTooltip("Prov. " + feature.properties.PROV);
            }
          });
        }
      },
      filter: function(feature, layer) {
        return (feature.properties.Kasus_Positif > 50);
      }
    });

    $.getJSON("geojson.php", function (data) {
      kasusrendah.addData(data);
      kasusagakrendah.addData(data);
      kasussedang.addData(data);
      kasustinggi.addData(data);
      var groupLayer = L.featureGroup([kasusrendah, kasusagakrendah, kasussedang, kasustinggi]);
      groupLayer.addTo(map);
      map.fitBounds(groupLayer.getBounds());
    });    

    var legend = new L.Control({position: 'bottomleft'});
    legend.onAdd = function (map) {
      this._div = L.DomUtil.create('div', 'info');
      this.update();
      return this._div;
    };
    legend.update = function () {
      this._div.innerHTML = '<h5>Legenda</h5><svg width="32" height="20"><rect width="32" height="17" style="fill:rgb(103, 171, 57);stroke-width:0.1;stroke:rgb(0,0,0)" /><text x="11" y="13.5" font-family="Verdana" font-size="14" fill="white">3</text></svg> Kasus 1 - 5<br><svg width="32" height="20"><rect width="32" height="17" style="fill:rgb(24, 85, 104);stroke-width:0.1;stroke:rgb(0,0,0)" /><text x="7" y="13.5" font-family="Verdana" font-size="14" fill="white">11</text></svg> Kasus 6 - 19<br><svg width="32" height="20"><rect width="32" height="17" style="fill:rgb(238, 138, 25);stroke-width:0.1;stroke:rgb(0,0,0)" /><text x="7" y="13.5" font-family="Verdana" font-size="14" fill="white">30</text></svg> Kasus 20 - 50<br><svg width="32" height="20"><rect width="32" height="17" style="fill:rgb(156, 39, 43);stroke-width:0.1;stroke:rgb(0,0,0)" /><text x="7" y="13.5" font-family="Verdana" font-size="14" fill="white">69</text></svg> Kasus >50<hr><small>Sumber data:<br><a href="https://api.kawalcorona.com/indonesia/provinsi/" target="_blank">https://api.kawalcorona.com/indonesia/provinsi/</a></small>'
    };
    legend.addTo(map);
  </script>
</body>
</html>