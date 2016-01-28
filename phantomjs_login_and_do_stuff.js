var page = new WebPage(), testindex = 0, loadInProgress = false;
phantom.cookiesEnabled = true;
someThingElseIsLoading = false;

page.settings.userAgent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:21.0) Gecko/20100101 Firefox/21.0';

page.onConsoleMessage = function(msg) {
  console.log(msg);
};

page.onLoadStarted = function() {
  loadInProgress = true;
	if(someThingElseIsLoading){
		console.log(" someThingElseIsLoading ");
	}
  console.log("load started");
};

page.onResourceRequested = function(requestData, networkRequest) {
    console.log('Request (#' + requestData.id + '): ' + JSON.stringify(requestData));
};


page.onResourceReceived = function(response) {
    console.log('\nResponse (#' + response.id + ', stage "' + response.stage + '"): ' + JSON.stringify(response));
};

page.onResourceError = function(resourceError) {
    console.log('\n****** : Unable to load resource (URL:' + resourceError.url + ')');
    console.log('****** : Error code: ' + resourceError.errorCode + '. Description: ' + resourceError.errorString);
};


page.onLoadFinished = function() {
  loadInProgress = false;
  console.log("load finished");
};

var steps = [
  function() {
    //Load Login Page
    page.open("https://exchange.pulsepoint.com/AccountMgmt/Login.aspx");
  },
  function() {
    //Enter Credentials
	someThingElseIsLoading = true ;
    page.evaluate(function() {
		$('#UserName').val('xyz@clovenetwork.com');
		$('#Password').val('somepass');
    });

  }, 
  function() {
    //Enter Credentials
	someThingElseIsLoading = true ;
    page.evaluate(function() {
		WebForm_DoPostBackWithOptions(new WebForm_PostBackOptions("LoginButton", "", true, "vgLogIn", "", false, true));
    });
  },

  function() {
	// Output content of page to stdout after form has been submitted
	page.evaluate(function(){
		console.log("Making Ajax request");
		//$(document.body).append('<div id="helloDiv"></div>');
		if($.ajax){
			console.log("ajax is present");
		}
		$.ajax({
			type: "POST",
			async: false,
			url: '/Publisher/UIWebServices/ReportingService.svc/GetAccountManagementData',
			data: {"rptInpData":{"AdTagGroupId":"68760","AdTagId":"-1","DateStart":"/Date(1369267200000)/","DateEnd":"/Date(1369267200000)/","PredefinedDateType":"yesterday","PredifinedDateTypeEnabled":true,"PricingOption":"both","ShowActiveTags":true}} ,
			dataType : 'json',
			success: function(data, textStatus){
				console.log(textStatus + ': ' + data);
			}
		});
	});
  }

];


interval = setInterval(function() {
  if (!loadInProgress && typeof steps[testindex] == "function") {
    console.log("step " + (testindex + 1));
    steps[testindex]();
    testindex++;
  }
  if (typeof steps[testindex] != "function") {
    console.log("test complete!");
    //phantom.exit();
  }
}, 2000);
