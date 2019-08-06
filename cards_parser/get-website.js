// var webPage = require('webpage');
// var page = webPage.create();
//
// page.open('http://localhost:8888/berserk/getcards.html', function(status) {
//   console.log(page.content);
//   phantom.exit();
// });

var page = require('webpage').create();
page.open('http://localhost:8888/berserk/getcards.html', function() {
  // page.open('vk.com', function() {
  setTimeout(function() {
    // page.render('google.png');
    console.log(page.content);
    phantom.exit();
  }, 200);
});