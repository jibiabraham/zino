// Set this to something unique to this client
Meteor.hostid = '409897502705';
// Our Meteor server is on the data. subdomain
Meteor.host = "universe."+location.hostname;
// Call the test() function when data arrives
Meteor.registerEventCallback("process", test);
// Join the demo channel and get last five events, then stream
Meteor.joinChannel("demo", 5);
Meteor.mode = 'stream';
// Start streaming!
Meteor.connect();
// Handle incoming events
function test(data) { alert( data ) };