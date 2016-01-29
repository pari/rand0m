/*
// Created by Chandu Nannapaneni on Jan 13 2015
Simple Casper script that logins in on your behalf to 
your fundsindia login and generates status.png
*/


var casper = require('casper').create();

casper.start('https://www.fundsindia.com/content/jsp/registration/login.jsp#SignIn', function() {});

casper.then(function() {
	this.viewport(1024, 900);
	this.fill('form#UserSignInForm', {
		'userMailId': 'REPLACE_WITH_USERNAME',
		'userPassword': 'REPLACE_WITH_PASSWORD'
	}, true);
});


casper.waitForSelector("form input[name='dobOrPan']", function() {
	this.fill('form[name="UserSignInForm"]', { "dobOrPan": 'REPLACE_WITH_DOBPAN' }, true);
}, true);


casper.then(function() {
	this.capture('status.png', {
	     top: 0,
	     left: 0,
	     width: 1500,
	     height: 1400
	 });
});

casper.run();
