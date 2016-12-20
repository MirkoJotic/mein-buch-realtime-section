var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var Redis = require('ioredis');
var redis = new Redis(6379, '127.0.0.1');

//var users = {};


io.on('connection', function(socket){

  //socket.on('notify of activity', function(data){
  //  console.log("---USER PRESENT---");
  //  console.log(data.email);
  //  if(data.email)
  //    updateActiveConnectionsConnect(socket, data.email);
  //
  //  io.emit('activity', users);
  //});

  //socket.on('disconnect', function(data){
  //  console.log("--- DISCONNECTED ---");
  //  updateAfterDisconnect(socket); 
  //  io.emit('activity', users);
  //});
});


// Disconet is every tab close
//function updateAfterDisconnect(socket) {
//  var connCount = users[socket.nickname];
//  if(connCount > 0)
//    connCount -= 1;
//
//  users[socket.nickname] = connCount;
//  console.log("users[socket.nickname]");
//  console.log(connCount);
//}

// New connection is created each new refresh or if new tab is opened with same account
//function updateActiveConnectionsConnect(socket, email) {
//    socket.nickname = email;
//    if(socket.nickname in users) {
//      users[socket.nickname] += 1;
//    } else {
//      users[socket.nickname] = 1;
//    }
//console.log(users);
//}

redis.psubscribe('*', function(err, count) {
  if(err) throw err;
  console.log("count: ");
  console.log(count);
});

redis.on('pmessage', function(pattern, channel, message) {
  message = JSON.parse(message);
  console.log(channel + ':' + message.event);
  io.emit(channel + ":" + message.event, message.data);
});

redis.on('error', function(err) {
    if(err) throw err;
    console.log("Redis is not running");
});

redis.on('ready', function(){
    console.log("Redis is running");
});

http.listen(3000, function(){
  console.log('Listening on PORT 3000');
});
