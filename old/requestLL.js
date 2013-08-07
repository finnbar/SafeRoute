var request = require('request')  //(nodejs' stuff)
  , lat = process.argv[2]   //latitude
  , lon = process.argv[3]   //longitude
  , type = process.argv[4]  //walk,car or bike
  ;

var x = 0; //total crimes
var w = 0; //walk
var c = 0; //car
var b = 0; //bike
var time = new Date();
var mnth = time.getMonth(); //month
var yr = time.getFullYear();//year
var walkCrime = ["all-crime","anti-social-behaviour","criminal-damage-arson","drugs","other-theft","possession-of-weapons","public-order","robbery","shoplifting","theft-from-the-person","violent-crime","other-crime"];
var bikeCrime = ["all-crime","other-theft","bicycle-theft","possession-of-weapons","public-order","robbery","shoplifting","theft-from-the-person","violent-crime","other-crime"];
var carCrime = ["all-crime","burglary","other-theft","possession-of-weapons","public-order","robbery","vehicle-crime","other-crime"];
mnth = mnth - 1;  //from here...
if(mnth < 0) {
  mnth = 11;
}
if(mnth < 10) {
  mnth = '0' + mnth;
}
/* 
to here is a function that takes month number (0-11),
subtracts one month (as data is updated monthly, so there will be
none for this month), and sets the month to 11 if the month is -1
(as the month may have been 0, so became -1).
*/
console.log(mnth,yr);

if(!lat || !lon) {
  throw new Error('sad panda');  //VERY IMPORTANT. SAD PANDA IS SAD :P
}

request(
  { method: 'GET'
  , json: true
  , uri: 'http://data.police.uk/api/crimes-street/all-crime?lat=' + lat + '&lng=' + lon + '&date=' + yr + '-' + mnth},
  function (err, data) {
    data.body.forEach(function (crime) { //per crime do
      console.log(crime);
      x += 1;
      if(type == "walk") {
        for(var x=0;x<walkCrime.length;x++) {
          if(walkCrime[x] == crime["category"]) {
            w += 1;
          }
        }
      }
      else if(type == "car") {
        for(var x=0;x<carCrime.length;x++) {
          if(carCrime[x] == crime["category"]) {
            c += 1;
            break;
          }
        }
      }
      else if(type == "bike") {
        for(var x=0;x<bikeCrime.length;x++) {
          if(bikeCrime[x] == crime["category"]) {
            b += 1;
            break;
          }
        }
      }
    })
    console.log(x);
    if(type == "walk") {
      console.log("walking crimes: " + w);
    } else if(type == "bike") {
      console.log("bike crimes: " + b);
    } else if(type == "car") {
      console.log("car crimes: " + c);
    }
});
