const express = require('express');
const bodyParser = require('body-parser');
const ffmpeg = require('fluent-ffmpeg');
const fs = require('fs');
const app = express();
const port = 3000;

app.use(bodyParser.urlencoded({ extended: true }));

// CORS middleware
app.use((req, res, next) => {
    res.setHeader('Access-Control-Allow-Origin', '*'); // Allow requests from any origin
    res.setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS'); // Allow GET, POST, OPTIONS requests
    res.setHeader('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept'); // Allow specified headers
    next();
});

// Route to handle GET requests
app.get('/process-variables', (req, res) => {
    var variable1 = req.query.variable1;
    var start = req.query.cuting_time;
    var duration = req.query.duration;
    var src = req.query.src;
    var parts = variable1.split("/");
    console.log("segmenting "+parts[1]+" "+parts[3]+" episode "+parts[4]);
    const directory = 'segmented/'+parts[1]+"/"+parts[3];
    fs.mkdir(directory, { recursive: true }, (err) => {
    if (err) {
        console.error('Error creating directory:', err);
    } 
});



    ffmpeg({source:"../website/"+variable1})
.setStartTime(start)
.duration(duration)
.on('start',function(commandLine){
  console.log("start")
})
.on('error',function(error){
  console.log(error)
})
.on('end',function(err){
  console.log("end")
})
.saveToFile(directory+"/"+parts[4])


    

    // Perform any processing with the variables here

    res.sendStatus(200); // Send a success response
});

app.listen(port, () => {
    console.log(`Server is running on port ${port}`);
});




/*ffmpeg()
.setStartTime(60)
.duration(10)
.on('start',function(commandLine){
  console.log("start")
})
.on('error',function(error){
  console.log("error")
})
.on('end',function(err){
  console.log("end")
})
.saveToFile("cute.mp4")*/















/*const ffmpeg = require('fluent-ffmpeg');

ffmpeg({source:'video.mp4'})
.setStartTime(60)
.duration(10)
.on('start',function(commandLine){
  console.log("start")
})
.on('error',function(error){
  console.log("error")
})
.on('end',function(err){
  console.log("end")
})
.saveToFile("cute.mp4")*/

