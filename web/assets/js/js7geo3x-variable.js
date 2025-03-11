/****** LAYOUT ******/
var refreshPage = true;

var firstLogMobile = "";
var topmenu = "";
var sidemenu = "";
var actionmenu = "";


var rememberOngletCartoPosition;
var rememberOngletRapport;
var rememberOngletGeofencing;
var rememberOngletPointInteret;
var rememberOngletConfiguration;
var rememberOngletOption;
var rememberOngletEtatBalise;

var globalIpDatabaseGpw;
var globalnomDatabaseGpw;
var globalIdDatabaseGpw;

var nomBaliseGlobal;
var nomGroupeGlobal;
var idBaliseGlobal;
var idGroupeGlobal;

var rememberAddMarkerGlobal;
var rememberAddPositionGlobal;
var rememberAddPeriodeGlobal;
var rememberDivHistoriqueGlobal = "Periode";

var numeroAppelGlobal;
var modeMessageGlobal = "GPRS";
var firmwareBaliseGlobal;
var versionBaliseGlobal;

/****** GOOGLEMAP ******/
//var marker;																											
var marker2;
var imageMarker;
//var circle;
var bounds;
var infowindow;							
var MarkersArray = new Array();	
var MarkersArrayPanorama = new Array();	
var map_poi;
var map;		
var map2;
var mapOptions;
var mapOptions2;
var statusStreet;
var panoramaOptions;
var panorama;
var latlongPanorama;
var iconPanorama;
var latlng;
var flightPath = [];
var flightPlanCoordinates = [];
var flightColors= [];
var markerMultipleBalise= [];
var infoMultipleBalise= [];
var latlngMultipleBalise= [];
var geocoder;
//var latPtInteret;
//var lngPtInteret;
var LatLngArray = new Array();		
			
/****** SCRIPT ******/
var i;
var date=new Date();
//var jour= date.getDate();
//var mois= date.getMonth();
//mois = mois + 1;
//if (mois < 10) { mois = '0' + mois; }
//var year= date.getYear();
//var heure= date.getHours();
//var minute= date.getMinutes();
//if (minute < 10) { minute = '0' + minute; }
//var seconde= date.getSeconds();
//var nompoi="POI - "+jour+"-"+mois+"-"+(year+1900)+"_ "+heure+"h"+minute;
var baliseIdArray = new Array();		
var baliseNameArray = new Array();		

/****** ALARMES ******/
var alarme1 = new Image();
alarme1.src = '../../assets/img/ICONES/alarme1.ico';

var alarme2 = new Image();
alarme1.src = '../../assets/img/ICONES/alarme1.ico';

var alarmeAlim = new Image();
alarmeAlim.src = '../../assets/img/ICONES/alarmeAlim.ico';

var alarmeAlim3232 = new Image();
alarmeAlim3232.src = '../../assets/img/ICONES/alarmeAlim3232.ico';

var alarmeBatterie = new Image();
alarmeBatterie.src = '../../assets/img/ICONES/alarmeBatterie.ico';

var alarmeMulti = new Image();
alarmeMulti.src = '../../assets/img/ICONES/alarmeMulti.ico';

/****** BROUILLAGES ******/
var brouillage = new Image();
brouillage.src = '../../assets/img/ICONES/brouillage.ico';

var nonBrouillage = new Image();
nonBrouillage.src = '../../assets/img/ICONES/nonBrouillage.ico';

/****** COULEURS ******/
var cJaune = new Image();
cJaune.src = '../../assets/img/ICONES/cJaune.ico';

var cRouge = new Image();
cRouge.src = '../../assets/img/ICONES/cRouge.ico';

var cVert = new Image();
cVert.src = '../../assets/img/ICONES/cVert.ico';

/****** FLECHES VERTES******/
var fleche0Deg = new Image();
fleche0Deg.src = '../../assets/img/ICONES/fleche0deg.ico';

var fleche45Deg = new Image();
fleche45Deg.src = '../../assets/img/ICONES/fleche45deg.ico';

var fleche90Deg = new Image();
fleche90Deg.src = '../../assets/img/ICONES/fleche90deg.ico';

var fleche135Deg = new Image();
fleche135Deg.src = '../../assets/img/ICONES/fleche135deg.ico';

var fleche180Deg = new Image();
fleche180Deg.src = '../../assets/img/ICONES/fleche180deg.ico';

var fleche225Deg = new Image();
fleche225Deg.src = '../../assets/img/ICONES/fleche225deg.ico';

var fleche270Deg = new Image();
fleche270Deg.src = '../../assets/img/ICONES/fleche270deg.ico';

var fleche315Deg = new Image();
fleche315Deg.src = '../../assets/img/ICONES/fleche315deg.ico';

/****** FLECHES JAUNES******/
var fleche0DegJaune = new Image();
fleche0DegJaune.src = '../../assets/img/ICONES/fleche0degJaune.ico';

var fleche45DegJaune = new Image();
fleche45DegJaune.src = '../../assets/img/ICONES/fleche45degJaune.ico';

var fleche90DegJaune = new Image();
fleche90DegJaune.src = '../../assets/img/ICONES/fleche90degJaune.ico';

var fleche135DegJaune = new Image();
fleche135DegJaune.src = '../../assets/img/ICONES/fleche135degJaune.ico';

var fleche180DegJaune = new Image();
fleche180DegJaune.src = '../../assets/img/ICONES/fleche180degJaune.ico';

var fleche225DegJaune = new Image();
fleche225DegJaune.src = '../../assets/img/ICONES/fleche225degJaune.ico';

var fleche270DegJaune = new Image();
fleche270DegJaune.src = '../../assets/img/ICONES/fleche270degJaune.ico';

var fleche315DegJaune = new Image();
fleche315DegJaune.src = '../../assets/img/ICONES/fleche315degJaune.ico';

/****** FLECHES ROUGES******/
var fleche0DegRouge = new Image();
fleche0DegRouge.src = '../../assets/img/ICONES/fleche0degRouge.ico';

var fleche45DegRouge = new Image();
fleche45DegRouge.src = '../../assets/img/ICONES/fleche45degRouge.ico';

var fleche90DegRouge = new Image();
fleche90DegRouge.src = '../../assets/img/ICONES/fleche90degRouge.ico';

var fleche135DegRouge = new Image();
fleche135DegRouge.src = '../../assets/img/ICONES/fleche135degRouge.ico';

var fleche180DegRouge = new Image();
fleche180DegRouge.src = '../../assets/img/ICONES/fleche180degRouge.ico';

var fleche225DegRouge = new Image();
fleche225DegRouge.src = '../../assets/img/ICONES/fleche225degRouge.ico';

var fleche270DegRouge = new Image();
fleche270DegRouge.src = '../../assets/img/ICONES/fleche270degRouge.ico';

var fleche315DegRouge = new Image();
fleche315DegRouge.src = '../../assets/img/ICONES/fleche315degRouge.ico';

/****** GPS******/
var noGPS = new Image();
noGPS.src = '../../assets/img/ICONES/noGPS.ico';

var noGPS_Stop = new Image();
noGPS_Stop.src = '../../assets/img/ICONES/noGPS_Stop.ico';

/****** POI / MARKER******/
var markerGreen = new Image();
markerGreen.src = '../../assets/img/ICONES/marker_green.png';

var poi = new Image();
poi.src = '../../assets/img/ICONES/POI.ico';

var poi1 = new Image();
poi1.src = '../../assets/img/ICONES/POI1.ico';


/**** AUTRE*****/
var stop16 = new Image();
stop16.src = '../../assets/img/ICONES/stop16.ico';

var timing = new Image();
timing.src = '../../assets/img/ICONES/Timing.ico';

var defaultVoiture = new Image();
defaultVoiture.src = '../../assets/img/ICONES/default.ico';

var positionFleche = new Image();