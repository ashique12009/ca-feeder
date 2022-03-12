//initiate script
trafficSrcCookie.setCookie();
var objTest = trafficSrcCookie.getCookie();
console.log(objTest);
// 	document.getElementById("ga-traffic-source-data").value = objTest.ga_source;
var val_ga_campaign = 'ga_campaign: ' + objTest.ga_campaign + ' || ';
var val_ga_content = 'ga_content: ' + objTest.ga_content + ' || ';
var val_ga_gclid = 'ga_gclid: ' + objTest.ga_gclid + ' || ';
var val_ga_keyword = 'ga_keyword: ' + objTest.ga_keyword + ' || ';
var val_ga_landing_page = 'ga_landing_page: ' + objTest.ga_landing_page + ' || ';
var val_ga_medium = 'ga_medium: ' + objTest.ga_medium + ' || ';
var val_ga_source = 'ga_source: ' + objTest.ga_source;
var ga_all_data = val_ga_campaign + val_ga_content + val_ga_gclid + val_ga_keyword + val_ga_landing_page + val_ga_medium + val_ga_source;
document.getElementById("ga-traffic-source-data").innerHTML = ga_all_data;
console.log(ga_all_data);