var mysql = require('mysql');
var express = require('express');
var siege = require('siege');
var json = require('json');

var con = mysql.createConnection({
    host: 'localhost',
    user: 'mdesilets',
    password: 'ehf4EaQ_CU(N',
    database: 'lesnormandeaudesilets_DEV'
});

var app = express();

con.connect(function (err) {
    if (!err) {
        console.log("Database is connected ... \n\n");
    } else {
        console.log("Error connecting database ... \n\n");
    }
});

app.get('/', function (req, res) {
    'use strict';
    con.query('call getAllYears', function (err, rows, fields) {
        con.end();
        if (!err) {
            console.log('The solution is: ', JSON.stringify(rows));
        } else
            console.log('Error while performing Query.');
    });
});

app.listen(3000);